<?php
include './conn.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['blog_id']) && isset($_GET['user_id'])) {
    $blog_id = $_GET['blog_id'];
    $user_id = $_GET['user_id'];

    $stmt = $conn->prepare("SELECT blog_title, blog_content FROM blog_data WHERE blog_id = ? AND user_id = ?");
    $stmt->bind_param("ss", $blog_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $blog_title = $row['blog_title'];
        $blog_content = $row['blog_content'];
    } else {
        echo "Blog not found or unauthorized access.";
        exit();
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $blog_id = $_POST['blog_id'];
    $user_id = $_POST['user_id'];
    $blog_title = mysqli_real_escape_string($conn, $_POST['blog_title']);
    $blog_content = mysqli_real_escape_string($conn, $_POST['blog_content']);

    // File upload handling
    if (!empty($_FILES['blogcover']['name'])) {
        $target_dir = "uploads/"; // Make sure this folder exists
        $blog_cover = $target_dir . basename($_FILES["blogcover"]["name"]);
        
        if (move_uploaded_file($_FILES["blogcover"]["tmp_name"], $blog_cover)) {
            $stmt = $conn->prepare("UPDATE blog_data SET blog_title = ?, blog_content = ?, blog_cover = ? WHERE blog_id = ? AND user_id = ?");
            $stmt->bind_param("sssss", $blog_title, $blog_content, $blog_cover, $blog_id, $user_id);
        } else {
            echo "Error uploading file.";
            exit();
        }
    } else {
        // No file uploaded, update without changing the cover
        $stmt = $conn->prepare("UPDATE blog_data SET blog_title = ?, blog_content = ? WHERE blog_id = ? AND user_id = ?");
        $stmt->bind_param("ssss", $blog_title, $blog_content, $blog_id, $user_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Blog updated successfully!'); window.location.href='display_my_blog.php?user_id=$user_id';</script>";
    } else {
        echo "Error updating blog.";
    }
    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Blog</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSRgGAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
    
    <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
    ></script>

    <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
    ></script>
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" href="style.css"></link>
</head>
<body>
<div class="container mt-4">
    <div class="div-design-editblog">
    <h2 class="text-center">Edit Blog</h2>
    <form method="POST" action="edit_blog.php" enctype="multipart/form-data">
        <input type="hidden" name="blog_id" value="<?php echo htmlspecialchars($blog_id); ?>">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        
        <div class="mb-3">
            <label class="form-label">Blog Title</label>
            <input type="text" name="blog_title" class="form-control" value='
                                        <?php
                                        $content = $row["blog_content"];
                                                        
                                        $content = str_replace(["\\r\\n", "\\r", "\\n"], "\n", $content);
                                                        
                                        $content = htmlspecialchars($content);
                                                        
                                        echo nl2br($content);?>
                                        
                                        ?>
        
             ' required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Blog Content</label>
            <textarea name="blog_content" class="form-control" rows="5" required><?php echo htmlspecialchars($blog_content); ?></textarea>
        </div>
        <div class="edit-blog-button">
            <button type="submit" name="update" class="btn btn-success">Update Blog</button>
            <a href="display_my_blog.php?user_id=<?php echo $user_id; ?>" class="btn btn-secondary">Go back</a>
        </div>
        <div class="mb-1 form-group ">
            <label class="form-label position-relative" for="blogcover">Blog Cover:</label>
            <input type="file"id="blogcover" name="blogcover"class="form-control w-100">
            <small id="blogcover_helpid" class="text-muted" hidden>Required</small> 
        </div>
    </form>
</div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>
</html>
