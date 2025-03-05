<?php
    session_start();   
    if (!isset($_SESSION['logged_in'])) { 
        header("Location: login_user.php");
        exit();
    }
    include './conn.php';
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';
    ?>
            
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blogs</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSRgGAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"/>
    <link rel="stylesheet" href="style.css"/>
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
          <a class="nav-link" href="logout.php?user_id=<?php echo htmlspecialchars($user_id); ?>">Logout</a>
        </li>
        <?php } else { ?>
        <li class="nav-item">
          <a class="nav-link" href="register_user.php?user_id=<?php echo htmlspecialchars($user_id); ?>">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login_user.php?user_id=<?php echo htmlspecialchars($user_id); ?>">Login</a>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>



    <div class="container mt-4">
        <h2 class="text-center mb-4">Blogs</h2>
        <div class="row">
            <div class="header-button">
                <a href="display_my_blog.php?user_id=<?php echo $user_id; ?>" class="btn btn-primary btn-display">View my own Blogs</a>
                <a href="create_blog.php?user_id=<?php echo $user_id; ?>" class="btn btn-primary btn-display">Create a new blog</a>
            </div>
            <?php
                $stmt = $conn->prepare("SELECT blog_title, blog_content,blog_category, datecreated, username, user_image, blog_cover, blog_id 
                                        FROM blog_data 
                                        JOIN users ON blog_data.user_id = users.user_id 
                                        WHERE blog_data.user_id != ?
                                        ORDER BY datecreated DESC");
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
            ?>
<div class="col-md-4 col-sm-6 col-12 mb-5 card-height">
    <div class="card border-secondary h-100 m-2">
        <?php if(isset($row["blog_cover"])){?>
        <img src="<?php echo htmlspecialchars($row["blog_cover"]); ?>" class="card-img-top" alt="blog cover">
        <?php 
    }   ?>
        <div class="card-body d-flex flex-column">
            <h5 class="card-title mb-0">
                <?php echo htmlspecialchars($row["blog_title"]); ?>
            </h5>
            <p class="card-content mb-0">
              <?php echo htmlspecialchars($row["blog_category"]);?>
            </p>
            <hr>
            <div class="d-flex">
                <div class="img-design-pfp me-2">
                    <img src="<?php echo htmlspecialchars($row["user_image"]); ?>" alt="User Image" class="rounded-circle img-fluid" width="40" height="40">
                </div>
                <div>
                <h6 class="card-subtitle mt-2 mb-1 text-muted">
                    @<?php echo htmlspecialchars($row["username"]); ?>
                </h6>
                    </div>
            </div>
            <p class="card-text mt-2">
        <?php
$content = $row["blog_content"];
$content = nl2br($content);

// Apply htmlspecialchars AFTER image replacement
echo $content;
?>

</p>

<div class="d-flex justify-content-between mt-auto">
  <a href="view_blog.php?blog_id=<?php echo $row['blog_id']; ?>&user_id=<?php echo $user_id; ?>&username=<?php echo urlencode($row['username']); ?>" 
   class="btn btn-light border-primary btn-display-viewblog ">
   Read Full Blog
</a>
  </div>
<h6 class="mt-2 mb-2">Posted on: <?php echo $row["datecreated"]; ?></h6>

        </div>
    </div>
</div>

            <?php
                    }
                } else {
                    echo "<div class='col-12 text-center'><p>No blogs found from other users.</p></div>";
                }
                $stmt->close();
                $conn->close();
            ?>
        </div>
    </div>
</body>

</html>
<script>
    $(document).ready(function(){
        
    })
</script>