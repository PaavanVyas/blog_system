<?php
session_start();
include './conn.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['blog_id'])) {
    $blog_id = $_GET['blog_id'];
    if(isset($_GET['user_id'])){
        $user_id = $_GET['user_id'];
    }
    
    $username = $_GET['username'];

    $stmt = $conn->prepare("SELECT blog_id, blog_title, blog_content, blog_cover, datecreated, user_id, blog_category FROM blog_data WHERE blog_id = ?");
    $stmt->bind_param("s", $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $blogid = $row['blog_id'];
        $blog_title = $row['blog_title'];
        $blog_content = $row['blog_content'];
        $blog_cover = $row['blog_cover'];
        $datecreated = $row['datecreated'];
        $blog_user_id = $row['user_id'];
        $blog_category = $row['blog_category'];
    } else {
        echo "Blog not found or unauthorized access.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="style.css">
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

<h2 class="text-center mt-2">View Blog</h2>
<div class="container img-blog-cover">
        <?php if (!empty($blog_cover)) { ?>
                <img src="<?php echo htmlspecialchars($blog_cover); ?>" class="card-img-top" alt="blog cover">
        <?php } ?>
        <div class="d-flex justify-content-between mt-2">
            <h4><?php echo htmlspecialchars($blog_title); ?></h4>
        </div>
        
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Share Link</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6>Copy Current URL:</h6>
        <p id="urlText"></p> <!-- Display URL here -->
        <button id="copyBtn" class="btn btn-primary">Copy</button>
    </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="container div-main-viewblog">
    
            <div class="div-design-viewblog">
                <div class=" container div-color-viewblog">
                    <div class="d-flex justify-content-between mt-2">
                        <h4><?php echo htmlspecialchars($blog_title);?></h4>
                        <p><b>By: <?php echo htmlspecialchars($username); ?></b></p>
                    </div>
                    <p><?php echo htmlspecialchars($blog_category);?></p>
            
            
                    <p class="card-text mt-2 flex-grow-1 overflow-hidden" style="min-height: 100px;">
                    <?php
                    $content = $blog_content;
                    $content = nl2br($content);

                    echo $content;
                    ?>

                    </p>


                    <center><a href="display_blog.php?user_id=<?php echo $user_id; ?>" class="btn btn-light border-primary">Go back</a></center>

                    <?php
                    if (isset($_SESSION["user_id_session"]) && $blog_user_id == $_SESSION["user_id_session"]) { ?>
                        <center><a href="edit_blog.php?blog_id=<?php echo $blogid; ?>&user_id=<?php echo $user_id; ?>&username=<?php echo urlencode($username); ?>" 
                           class="btn btn-light border-primary mt-2">Edit</a></center>
                    <?php } ?>
                    
                    <p>Posted on: <?php echo htmlspecialchars($datecreated);?></p>
                </div>

            <div class="div-design-viewblog-recommendation">
                <?php
                if (isset($_SESSION["user_id_session"]) && $blog_user_id != $_SESSION["user_id_session"]) { ?>
                    <h4>Other Blogs by <?php echo htmlspecialchars($username); ?></h4>
                        <?php
                            $recommend_stmt = $conn->prepare("SELECT blog_id, blog_title FROM blog_data WHERE blog_id != ? AND user_id = ? ORDER BY datecreated DESC");
                            $recommend_stmt->bind_param("ss", $blog_id, $blog_user_id);
                            $recommend_stmt->execute();
                            $result_recommend = $recommend_stmt->get_result();

                            while ($row_recommend = $result_recommend->fetch_assoc()) { ?>
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item border-success-subtle m-1">
                                        <a class="link-danger link-underline-opacity-75-hover"
                                           href="view_blog.php?blog_id=<?php echo $row_recommend['blog_id']; ?>&user_id=<?php echo $user_id; ?>&username=<?php echo urlencode($username); ?>">
                                            <p class="accordion-header accordian-text">
                                                <?php echo $row_recommend["blog_title"]; ?>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            <?php }
                            $recommend_stmt->close();
                }
                        ?>

            </div>
</div>
</div>

<div class="container border div-design-comment">

<?php
            if (isset($user_id)){
            if ($blog_user_id != $user_id)
            {
            ?>
            <div class=" container mt-2 border div-design-add-comment">
                <h4>Add a Comment</h4>
                <form action="create_comment.php" method="POST" id="replydata">

                    <div class="mb-1 form-group ">
                        
                        <textarea type="comment_content" id="comment_content" name="comment_content" class="form-control" required ></textarea>
                        <input type="hidden" value="<?php echo $user_id; ?>" id="user_id" name="user_id">
                        <input type="hidden" value="<?php echo $blog_id; ?>" id="blog_id" name="blog_id">
                        <input type="hidden" value="<?php echo $username; ?>" id="username" name="username">
                    </div>
                    <div>
                        <center><input type="submit" value="Add Comment" class = "mb-2"></center>
                    </div>
                </form>
            </div>

            <?php
                }
            }
            ?>

            
            <h4>Comments</h4>
            
            <div class="container mt-3 comment-border">  
            <?php
                $stmt_comment = $conn->prepare("SELECT * FROM comments_data JOIN users ON comments_data.user_id = users.user_id WHERE blog_id = ?");
                $stmt_comment->bind_param("s", $blog_id);
                $stmt_comment->execute();
                $result = $stmt_comment->get_result();
                    
                if ($result->num_rows > 0) { 
                    while ($row = $result->fetch_assoc()) {
                        $comment_id = $row["comment_id"];
            ?>
                  
                <div>
                        <label class="mt-3"><b>@<?php echo $row["username"];?></b></label>
                </div>
                <div>
                    <label><?php echo $row["comment_content"]; ?></label>
                </div>
                <?php
                    $stmt_reply = $conn -> prepare("SELECT reply,comment_id,user_id,reply_id FROM reply_data WHERE comment_id=?");
                    $stmt_reply->bind_param("s",$comment_id);
                    $stmt_reply->execute();
                    $result_reply = $stmt_reply->get_result();
                    if($result_reply->num_rows>0){
                ?>
                <button class="viewreply btn-reply">View Reply</button>
                <div class="display_replies" hidden>
                <label><b>@<?php echo htmlspecialchars($username); ?></b></label>
                <?php
                    while ($row_reply = $result_reply->fetch_assoc()) { ?>
                
                <label><?php echo htmlspecialchars($row_reply["reply"]); ?></label><br>
                    <?php } 
                echo "</div>";
                    }
                    if(isset($user_id)){
                    if($blog_user_id == $user_id){
                ?>
                    <form action="create_reply.php" method="POST">
                        <div>
                            <textarea id="reply_content" name="reply_content" required class="form-control w-50"></textarea>
                        </div>
                        <input type="hidden" value="<?php echo $user_id; ?>" id="user_id" name="user_id">
                        <input type="hidden" value="<?php echo $blog_id; ?>" id="blog_id" name="blog_id">
                        <input type="hidden" value="<?php echo $row['comment_id']; ?>" id="comment_id" name="comment_id">
                        <input type="hidden" value="<?php echo $username; ?>" id="username" name="username">
                        <input type="submit" value="Reply" class="btn btn-light border-primary mt-2 d-flex">    
                    </form>
                </div>
                <?php  
                 }        
                }
                
                }
                echo "</div>";
                } else {
                echo "No comments Yet!";
                }?>

            </div>
            </div>
            
<?php

$stmt->close();
$conn->close();
?>
</body>
</html>

<script>
    $(document).ready(function () {
    $(".viewreply").click(function () {
        let btn = $(this); 
        let repliesDiv = btn.next(".display_replies");
        
        console.log(repliesDiv); // Debugging

        if (btn.text().trim() === "View Reply") {
            repliesDiv.removeAttr("hidden");
            btn.text("Hide Reply");
        } else if (btn.text().trim() === "Hide Reply") {
            repliesDiv.attr("hidden", true);
            btn.text("View Reply");
        } else {
            console.error("There is some error in the code");
            throw new Error("Unexpected button text: " + btn.text().trim());
        }

        console.log("Toggled visibility of replies.");
    });
});


</script>

  

