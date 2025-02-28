<?php
session_start();
include "conn.php"; // Make sure to include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare and execute the query securely
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        session_start(); // Always start the session

if (password_verify($password, $row["password"])) {
    // Store user data securely in the session
    $_SESSION["user_id_session"] = $row["user_id"];
    $_SESSION["logged_in"] = true;

    // Redirect securely to the blog page
    header("Location: display_blog.php?user_id=" . urlencode($row['user_id']));
    exit();
} else {
    echo "<script>alert('Invalid password!'); window.history.back();</script>";
}

    } else {
        echo "User not found!";
    }

    $stmt->close();
    $conn->close();
}
?>
