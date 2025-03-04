<?php
include './conn.php';

$user_id = generateCustomerID($conn);
$firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
$lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$country = mysqli_real_escape_string($conn, $_POST['country']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$hashedpassword = password_hash($password, PASSWORD_DEFAULT);

// Directory where images will be stored
$targetdirectory = "uploads/";
$targetfile = $targetdirectory . basename($_FILES["image"]["name"]); // Fixed missing semicolon
$imageFileType = strtolower(pathinfo($targetfile, PATHINFO_EXTENSION));
$uploadOk = 1;

// Validate image
$check = getimagesize($_FILES["image"]["tmp_name"]);
if ($check === false) {
    die("File is not an image.");
    $uploadOk = 0;
}

// Check file size (max 2MB)
if ($_FILES["image"]["size"] > 2000000) {
    die("Sorry, your file is too large.");
    $uploadOk = 0;
}

// Allow only certain file formats
$allowed_formats = ["jpg", "jpeg", "png"];
if (!in_array($imageFileType, $allowed_formats)) {
    die("Sorry, only JPG, JPEG & PNG files are allowed.");
    $uploadOk = 0;
}

// Check if file upload is allowed
if ($uploadOk == 1) {
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetfile)) {
        die("Error uploading file.");
    }
}

// Email validation
$emailvalidate = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($emailvalidate);
$stmt->bind_param("s", $email);
$stmt->execute();
$result_email = $stmt->get_result();
$rowcount = $result_email->num_rows;
$stmt->close();

if ($rowcount > 0) {
    header("Location: register_user.php?email_exists=true"); 
    exit(); 
}

// Username validation
$usernamevalidate = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($usernamevalidate);
$stmt->bind_param("s", $username);
$stmt->execute();
$result_username = $stmt->get_result();
$rowcount = $result_username->num_rows;
$stmt->close();

if ($rowcount > 0) {
    header("Location: register_user.php?username_exists=true"); 
    exit(); 
}

// Insert into database
$query = "INSERT INTO users (user_id, first_name, last_name, username, email, password, country, user_image) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("ssssssss", $user_id, $firstname, $lastname, $username, $email, $hashedpassword, $country, $targetfile);
$result = $stmt->execute();

if ($result) {
    echo "User Registered Successfully";
    header("Location: login_user.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Function to generate unique user ID
function generateCustomerID($conn) {
    do {
        $random_number = random_int(100000, 999999);
        $UserID = 'U' . $random_number;

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
