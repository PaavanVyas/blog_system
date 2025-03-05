<?php
    include './conn.php';
    if (!isset($_SESSION['logged_in'])) { 
      header("Location: login_user.php");
      exit();
  }
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
?>
            
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Blogs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-color-card">

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php?user_id=<?php echo htmlspecialchars($user_id); ?>">
      <img src="./images/invennico_logo-removebg.PNG" alt="Retailer" style="height: 40px;">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="display_blog.php?user_id=<?php echo htmlspecialchars($user_id); ?>">Blogs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="display_my_blog.php?user_id=<?php echo htmlspecialchars($user_id); ?>">My Blogs</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="create_blog.php?user_id=<?php echo htmlspecialchars($user_id); ?>">Create New Blog</a>
        </li>
        <?php if(isset($_SESSION['user_id_session'])) { ?> 
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
        <?php } else { ?>
        <li class="nav-item">
          <a class="nav-link" href="register_user.php">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login_user.php">Login</a>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center mb-4">My Blogs</h2>
    <div class="row">
        <div class="header-button">
            <a href="display_blog.php?user_id=<?php echo $user_id; ?>" class="btn btn-primary">Go Back</a>
            <a href="create_blog.php?user_id=<?php echo $user_id; ?>" class="btn btn-primary">Create a new blog</a>
        </div>

        <?php
        // Fetch user's blogs
        $stmt = $conn->prepare("SELECT blog_title, blog_content, datecreated, username, blog_id, blog_cover 
                                FROM blog_data 
                                JOIN users ON blog_data.user_id = users.user_id 
                                WHERE blog_data.user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $blog_id = $row["blog_id"];

                // Fetch images for this specific blog
                $stmt_images = $conn->prepare("SELECT image_url FROM blog_images WHERE blog_id = ?");
                $stmt_images->bind_param("s", $blog_id);
                $stmt_images->execute();
                $result_images = $stmt_images->get_result();
                $images = [];
                while ($img_row = $result_images->fetch_assoc()) {
                    $images[] = $img_row['image_url'];
                }
                $stmt_images->close();
        ?>
        <div class="col-md-4 col-sm-6 col-12 mb-5 card-height">
            <div class="card border-secondary h-100 m-2">
                <?php if (!empty($row["blog_cover"])) { ?>
                    <img src="<?php echo htmlspecialchars($row["blog_cover"]); ?>" class="card-img-top" alt="blog cover">
                <?php } ?>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($row["blog_title"]); ?></h5>
                    <hr>
                    <div class="d-flex">
                        <h6 class="card-subtitle mt-2 mb-1 text-muted">@<?php echo htmlspecialchars($row["username"]); ?></h6>
                    </div>
                    <p class="card-text mt-2 flex-grow-1 overflow-hidden" style="min-height: 100px;">
        <?php
$content = $row["blog_content"];
$content = nl2br($content);

// Apply htmlspecialchars AFTER image replacement
echo $content;
?>

</p>  
                    
                    <?php if (!empty($images)): ?>
                        <?php foreach ($images as $image): ?>
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="Blog Image" style="max-width: 100%; height:100%; margin-bottom: 10px; overflow:hidden;">
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="btn-design-card">
                    <a href="edit_blog.php?blog_id=<?php echo $blog_id; ?>&user_id=<?php echo $user_id; ?>" class="btn btn-light border-primary mb-2 mw-10">
                        Edit
                    </a>
                    <a href="view_blog.php?blog_id=<?php echo $blog_id; ?>&user_id=<?php echo $user_id; ?>&username=<?php echo urlencode($row['username']); ?>" class="btn btn-light border-primary">
                        View Full Blog
                    </a>
                        </div>
                    <h6 class="mt-2 mb-2">Posted on: <?php echo $row["datecreated"]; ?></h6>
                </div>
            </div>
        </div>
        <?php
            }
        } else {
            echo "<div class='col-12 text-center'><p>You have not posted any blogs yet.</p><p>Click 'Create a new blog' to get started.</p></div>";
        }
        $stmt->close();
        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
