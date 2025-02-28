<?php
session_start();  // Ensure session is started
include './conn.php';

// Retrieve data from POST request
$blogid = generateblogid($conn);
$blogtitle = mysqli_real_escape_string($conn, $_POST['blogtitle']);
$blogcontent = mysqli_real_escape_string($conn, $_POST['blogcontent']);
$datecreated = date('Y-m-d');  // Fixed format (YYYY-MM-DD)

// Ensure `user_id` is correctly retrieved
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';

if (!$user_id) {
    die("Error: User ID is missing.");
}

// Debugging output
echo "User ID: " . $user_id;

// Insert blog into `blog_data`
$query = "INSERT INTO `blog_data` (blog_id, user_id, blog_title, blog_content, datecreated)
          VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("sssss", $blogid, $user_id, $blogtitle, $blogcontent, $datecreated);

if ($stmt->execute()) {
    echo "Blog Created Successfully";
    header("Location: display_blog.php?user_id=" . urlencode($user_id)); 
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Function to generate a unique Blog ID
function generateblogid($conn) {
    do {
        $random_number = random_int(100000, 999999);
        $blogID = 'B' . $random_number;

        $sql  = "SELECT COUNT(*) AS count FROM blog_data WHERE blog_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $blogID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

    } while ($row['count'] > 0);

    return $blogID;
}
?>
