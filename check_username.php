<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username exists, return a response indicating this
        echo json_encode(["exists" => true]);
    } else {
        // Username doesn't exist, return a response indicating this
        echo json_encode(["exists" => false]);
    }


    $stmt->close();
    $conn->close();
}
?>
