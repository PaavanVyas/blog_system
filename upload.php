<?php
header('Content-Type: application/json');

// Initialize response array
$response = [
    "success" => false,
    "message" => "",
    "images" => [],
    "errors" => []
];

// Check if files were uploaded
if (isset($_FILES['blog_images']) && !empty($_FILES['blog_images']['name'][0])) {

    // Loop through each file
    $target_directory = "uploads/";
    
    // Create the uploads directory if it doesn't exist
    if (!is_dir($target_directory)) {
        mkdir($target_directory, 0777, true);
    }

    $img_count = 0;
    
    // Loop through each image
    foreach ($_FILES['blog_images']['name'] as $key => $image_name) {
        if ($_FILES['blog_images']['error'][$key] === 0 && !empty($image_name)) {
            $tmp_name = $_FILES['blog_images']['tmp_name'][$key];
            $unique_name = time() . "_{$img_count}_" . basename($image_name); 
            $image_url = $target_directory . $unique_name;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($tmp_name, $image_url)) {
                // Store image URL in the response array
                $response["images"][] = $image_url;
                $img_count++;
            } else {
                $response["errors"][] = "Failed to upload file: $image_name";
            }
        }
    }

    // If at least one image was uploaded successfully
    if (!empty($response["images"])) {
        $response["success"] = true;
        $response["message"] = "Images uploaded successfully.";
    } else {
        $response["message"] = "No images were uploaded.";
    }
} else {
    $response["message"] = "No images selected.";
}

// Return the response as JSON
echo json_encode($response);
exit();
?>
