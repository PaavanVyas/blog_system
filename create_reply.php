<?php
include './conn.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get values from the form
    $comment_id = $_POST["comment_id"];

    if(isset($_POST['reply_content'])) {
        $reply = $_POST['reply_content'];
    } else {
        echo "Reply not received";
        exit();
    }

    // Generate Reply ID
    $reply_id = generateReplyID($conn); 
    $date_created = date("Y-m-d");

    // Fetch blog_id, user_id, and username from POST
    $blog_id = $_POST['blog_id'];  
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];

    // Insert the reply into the database
    $sql = "INSERT INTO reply_data(reply_id, user_id, comment_id, reply, datecreated) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $reply_id, $user_id, $comment_id, $reply, $date_created);

    if($stmt->execute()) {
        // Redirect back to the blog page with the same parameters
        header("Location: view_blog.php?blog_id=$blog_id&user_id=$user_id&username=$username");
        exit();
    } else {
        echo "Unable to create reply";
    }
}

// Function to generate a unique reply ID
function generateReplyID($conn) {
    do {
        $random_number = random_int(100000, 999999);
        $replyID = 'R' . $random_number;

        // Check if the reply ID already exists
        $sql  = "SELECT COUNT(*) AS count FROM reply_data WHERE reply_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $replyID);
        $stmt->execute();   
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

    } while ($row['count'] > 0);
    
    return $replyID;
}
?>
