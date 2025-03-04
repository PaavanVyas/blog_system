<?php
include './conn.php';

$blog_title = $blog_content = "";
$existing_images = [];

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['blog_id']) && isset($_GET['user_id'])) {
    $blog_id = $_GET['blog_id'];
    $user_id = $_GET['user_id'];

    // Fetch Blog Data
    $stmt = $conn->prepare("SELECT blog_title, blog_content, blog_cover FROM blog_data WHERE blog_id = ? AND user_id = ?");
    $stmt->bind_param("ss", $blog_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $blog_title = $row['blog_title'];
        $blog_content = $row['blog_content'];
        $blog_cover = $row['blog_cover']; // Cover image path
    } else {
        echo "Blog not found or unauthorized access.";
        exit();
    }
    $stmt->close();

    // Fetch Existing Images
    $stmt_imgs = $conn->prepare("SELECT image_url FROM blog_images WHERE blog_id = ?");
    $stmt_imgs->bind_param("s", $blog_id);
    $stmt_imgs->execute();
    $result_imgs = $stmt_imgs->get_result();

    while ($img = $result_imgs->fetch_assoc()) {
        $existing_images[] = $img['image_url'];
    }
    $stmt_imgs->close();
}

// Handle Blog Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $blog_id = $_POST['blog_id'];
    $user_id = $_POST['user_id'];
    $blog_title = mysqli_real_escape_string($conn, $_POST['blog_title']);
    $blog_content = mysqli_real_escape_string($conn, $_POST['blog_content']);

    $conn->begin_transaction();
    try {
        // Handle Cover Image Update
        if (!empty($_FILES['blogcover']['name'])) {
            $target_dir = "uploads/";
            $cover_image_name = time() . "_" . basename($_FILES["blogcover"]["name"]);
            $blog_cover = $target_dir . $cover_image_name;
            
            if (move_uploaded_file($_FILES["blogcover"]["tmp_name"], $blog_cover)) {
                $stmt = $conn->prepare("UPDATE blog_data SET blog_title = ?, blog_content = ?, blog_cover = ? WHERE blog_id = ? AND user_id = ?");
                $stmt->bind_param("sssss", $blog_title, $blog_content, $blog_cover, $blog_id, $user_id);
            } else {
                throw new Exception("Error uploading cover image.");
            }
        } else {
            $stmt = $conn->prepare("UPDATE blog_data SET blog_title = ?, blog_content = ? WHERE blog_id = ? AND user_id = ?");
            $stmt->bind_param("ssss", $blog_title, $blog_content, $blog_id, $user_id);
        }

        $stmt->execute();

        // Handle Multiple Images Upload
        if (!empty($_FILES['blog_images']['name'][0])) {
            $target_directory = "uploads/";
            $uploaded_images = [];

            foreach ($_FILES['blog_images']['name'] as $key => $image_name) {
                if ($_FILES['blog_images']['error'][$key] === 0 && !empty($image_name)) {
                    $tmp_name = $_FILES['blog_images']['tmp_name'][$key];
                    $unique_name = time() . "_{$key}_" . basename($image_name);
                    $image_url = $target_directory . $unique_name;

                    if (move_uploaded_file($tmp_name, $image_url)) {
                        $stmt_img = $conn->prepare("INSERT INTO blog_images (blog_id, image_url) VALUES (?, ?)");
                        $stmt_img->bind_param("ss", $blog_id, $image_url);
                        $stmt_img->execute();

                        $uploaded_images[] = "<img src='$image_url' class='img-thumbnail' width='100'>";
                    } else {
                        throw new Exception("Failed to upload $image_name.");
                    }
                }
            }

            // Append new images to content
            if (!empty($uploaded_images)) {
                $blog_content .= "<br>" . implode(" ", $uploaded_images);
                $stmt_update = $conn->prepare("UPDATE blog_data SET blog_content = ? WHERE blog_id = ?");
                $stmt_update->bind_param("ss", $blog_content, $blog_id);
                $stmt_update->execute();
            }
        }

        $conn->commit();
        echo "<script>alert('Blog updated successfully!'); window.location.href='display_my_blog.php?user_id=$user_id';</script>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error updating blog: " . $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2 class="my-4">Edit Blog</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="blog_id" value="<?= htmlspecialchars($blog_id) ?>">
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">

        <div class="mb-3">
            <label class="form-label">Blog Title:</label>
            <input type="text" class="form-control" name="blog_title" value="<?= htmlspecialchars($blog_title) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Blog Content:</label>
            <textarea class="form-control" name="blog_content" rows="5" required><?= htmlspecialchars($blog_content) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Cover Image:</label><br>
            <?php if (!empty($blog_cover)) : ?>
                <img src="<?= $blog_cover ?>" class="img-thumbnail" width="150"><br>
            <?php endif; ?>
            <label class="form-label">Update Cover Image:</label>
            <input type="file" class="form-control" name="blogcover">
        </div>

        <div class="mb-3">
            <label class="form-label">Current Images:</label><br>
            <?php if (!empty($existing_images)) : ?>
                <?php foreach ($existing_images as $image) : ?>
                    <img src="<?= $image ?>" class="img-thumbnail" width="100">
                <?php endforeach; ?>
            <?php else : ?>
                <p>No images uploaded.</p>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Additional Images:</label>
            <input type="file" class="form-control" name="blog_images[]" multiple>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update Blog</button>
        <a href="display_my_blog.php?user_id=<?= htmlspecialchars($user_id) ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
