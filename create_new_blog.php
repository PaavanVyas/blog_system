<?php 
include './conn.php';

$response = ["success" => false, "message" => "", "images" => [], "errors" => []];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST["user_id"];
    $blog_title = $_POST["blog_title"];
    $blog_content = $_POST["blog_content"];
    $blog_category = $_POST["blog_category"] ?? "Uncategorized"; 
    $datecreated = date("Y-m-d");

    $blog_id = "B" . substr(time(), -5);

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO blog_data (blog_id, user_id, blog_title, blog_content, blog_category, datecreated) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $blog_id, $user_id, $blog_title, $blog_content, $blog_category, $datecreated);

        if ($stmt->execute()) {
            $response["success"] = true;
            $response["message"] = "Blog inserted successfully.";
            $response["blog_id"] = $blog_id;

            $target_directory = "uploads/";

            if (!is_dir($target_directory)) {
                mkdir($target_directory, 0777, true);
            }

            if (isset($_FILES['blog_cover']) && $_FILES['blog_cover']['error'] === 0) {
                $cover_image_name = $_FILES['blog_cover']['name'];
                $cover_tmp_name = $_FILES['blog_cover']['tmp_name'];
                $cover_unique_name = time() . "_" . basename($cover_image_name);
                $cover_image_url = $target_directory . $cover_unique_name;

                if (move_uploaded_file($cover_tmp_name, $cover_image_url)) {
                    
                    $stmt_cover = $conn->prepare("UPDATE blog_data SET blog_cover = ? WHERE blog_id = ?");
                    $stmt_cover->bind_param("ss", $cover_image_url, $blog_id);
                    if ($stmt_cover->execute()) {
                        $response["message"] .= " Blog cover uploaded successfully.";
                    } else {
                        $response["errors"][] = "Failed to update blog cover image.";
                    }
                } else {
                    $response["errors"][] = "Failed to upload blog cover image.";
                }
            }

            if (!empty($_FILES['blog_images']['name'][0])) {
                $img_count = 0;   // Track correct image numbering
                foreach ($_FILES['blog_images']['name'] as $key => $image_name) {
                    if ($_FILES['blog_images']['error'][$key] === 0 && !empty($image_name)) {
                        $tmp_name = $_FILES['blog_images']['tmp_name'][$key];
                        $unique_name = time() . "_{$key}_" . basename($image_name);
                        $image_url = $target_directory . $unique_name;
                        $placeholder = "[img$key]";

                        if (move_uploaded_file($tmp_name, $image_url)) {
                            $stmt_img = $conn->prepare("INSERT INTO blog_images (blog_id, image_url, placeholder) VALUES (?, ?, ?)");
                            $stmt_img->bind_param("sss", $blog_id, $image_url, $placeholder);
                            $stmt_img->execute();
                
                            $response["images"][] = ["url" => $image_url, "placeholder" => $placeholder];
                        } else {
                            $response["errors"][] = "Failed to upload $image_name";
                        }
                    }
                }

                foreach ($response["images"] as $image) {
                    $blog_content = str_replace($image["placeholder"], "<img src='" . $image["url"] . "' alt='image'>", $blog_content);
                }

                $stmt_update = $conn->prepare("UPDATE blog_data SET blog_content = ? WHERE blog_id = ?");
                $stmt_update->bind_param("ss", $blog_content, $blog_id);
                if ($stmt_update->execute()) {
                    $response["message"] .= " Images successfully embedded into blog content.";
                } else {
                    $response["errors"][] = "Failed to update blog content with images.";
                }
            }

            $conn->commit();
            header("Location: display_blog.php?user_id=$user_id");
            exit();

        } else {
            $conn->rollback();
            $response["message"] = "Failed to insert blog.";
        }
    } catch (Exception $e) {
        $conn->rollback();
        $response["message"] = "Error: " . $e->getMessage();
    }
}

echo json_encode($response);
