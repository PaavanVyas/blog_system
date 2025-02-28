<?php
include './conn.php';
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $comment_content = $_POST["comment_content"];
        $user_id = $_POST["user_id"];
        $blog_id = $_POST["blog_id"];
        $username = $_POST['username'];
        $comment_id = generateCommentID($conn); 
        $date_created = date("Y-m-d");
        header("Location:display_blog.php?user_id=$user_id");
        $sql = "INSERT INTO comments_data (comment_id, user_id, blog_id, comment_content, datecreated) 
        VALUES ('$comment_id', '$user_id', '$blog_id', '$comment_content', '$date_created')";
        if($conn->query($sql)===TRUE){
            echo "Comment created";
            header("Location: view_blog.php?blog_id=$blog_id&user_id=$user_id&username=$username");
        
            exit();
        }
        else{
            echo "Error in creating comment";
        }

        
    }
    function generateCommentID($conn) {
        do {
            $random_number = random_int(100000, 999999);
            $UserID = 'C' . $random_number;
    
            // Check if UserID already exists
            $sql  = "SELECT COUNT(*) AS count FROM users WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $UserID);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
    
        } while ($row['count'] > 0);
        
        return $UserID;
    }
?>