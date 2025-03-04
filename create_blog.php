<?php

    session_start();
    $user_id = $_GET['user_id'];
    include './conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Blog</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>




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
    <script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ JavaScript Loaded!");

    const textarea = document.getElementById("blog_content");
    const uploadImage = document.getElementById("uploadImage");

    if (!textarea || !uploadImage) {
        console.error("❌ Textarea or file input not found.");
        return;
    }

    let uploadedImages = [];
    const uploadedImagesField = document.createElement("input");
    uploadedImagesField.type = "hidden";
    uploadedImagesField.name = "uploaded_images";
    document.querySelector("form").appendChild(uploadedImagesField);

    uploadImage.addEventListener("change", function (event) {
        console.log("📂 File input changed!");

        const files = event.target.files;
        console.log("📂 Selected Files:", files.length);

        if (!files.length) return;

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            console.log(`📤 Adding File: ${files[i].name}`);
            formData.append("blog_images[]", files[i]);
        }

        fetch("upload.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("✅ Server Response:", data);
            if (data.success && data.images.length > 0) {
                data.images.forEach((img, index) => {
                    const placeholder = `[img${uploadedImages.length}]`;
                    const imgTag = `<img src="${img}" alt="image" />`; // Create the <img> tag

                    // Add the placeholder to the content in the textarea
                    textarea.value += " " + placeholder + " ";
                    uploadedImages.push(img);

                    // Replace the placeholder with the actual <img> tag
                    textarea.value = textarea.value.replace(placeholder, imgTag);
                });

                // Store the uploaded images' URLs in the hidden field
                uploadedImagesField.value = JSON.stringify(uploadedImages);
            } else {
                alert("❌ Image upload failed: " + (data.error || "Unknown error"));
            }
        })
        .catch(error => console.error("⚠️ Fetch Error:", error));
    });
});
</script>

    </head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php?user_id=<?php echo htmlspecialchars($user_id); ?>">
      <img src="./images/invennico_logo-removebg.PNG" alt="Retailer" style="height: 40px;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="display_blog.php?user_id=<?php echo htmlspecialchars($user_id); ?>">Blogs</a></li>
        <li class="nav-item"><a class="nav-link" href="display_my_blog.php?user_id=<?php echo htmlspecialchars($user_id); ?>">My Blogs</a></li>
        <li class="nav-item"><a class="nav-link" href="create_blog.php?user_id=<?php echo htmlspecialchars($user_id); ?>">Create New Blog</a></li>

        <?php if (isset($_SESSION['user_id_session'])) { ?>
        <li class="nav-item"><a class="nav-link" href="logout.php?user_id=<?php echo htmlspecialchars($user_id); ?>">Logout</a></li>
        <?php } else { ?>
        <li class="nav-item"><a class="nav-link" href="register_user.php">Register</a></li>
        <li class="nav-item"><a class="nav-link" href="login_user.php">Login</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>
    <div class="div-design-register">
    <h2>Create new Blog</h2>
    <div class="container form-container div-design">
    <div class="mb-1 form-group ">
    <form action="create_new_blog.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_GET['user_id']); ?>">
    </div>

    <div class="mb-1 form-group ">
        <label class="form-label position-relative">Title:</label>
        <input type="text" name="blog_title" required class="form-control w-100"><br>
    <div>

    <div class="mb-1 form-group ">
        <label class="form-label position-relative">Content:</label>
        <textarea id="blog_content" name="blog_content" placeholder="Write your blog here..." class="form-control w-100"></textarea>
    </div>
    <div class="mb-1 form-group ">
        <label class="form-label position-relative">Upload Images inside blog from here:</label>
        <input type="file" id="uploadImage" name="blog_images[]" multiple class="form-control w-100">
    </div>
    <div class="mb-1 form-group ">
        <label class="form-label position-relative">Upload Blog Cover:</label>
        <input type="file" name="blog_cover" accept="image/*" class="form-control w-100"><br>
</div>
<div class="mb-1 form-group">
    <label class="form-label position-relative">Select category</label>
    <select name="blog_category" class="form-control">
        <option value="">-- Select a category --</option> 
        <?php
            $sql_category = "SELECT * FROM blog_category";
            $result_category = $conn->query($sql_category);

            if ($result_category->num_rows > 0) {
                while ($row_category = $result_category->fetch_assoc()) { ?>
                    <option value="<?php echo $row_category['blog_category_name']; ?>">
                        <?php echo htmlspecialchars($row_category['blog_category_name']); ?>
                    </option>
                <?php }
            }
        ?>
    </select>
</div>


    <center><button type="submit">Post Blog</button></center>
</form>
</div>
</div>
</body>
</html>
