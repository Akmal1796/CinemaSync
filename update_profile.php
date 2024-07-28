<?php
session_start();
require_once './conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $user_id = $_SESSION['User_ID'];
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $profilePicture = null;

    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/profile_pictures/';
        $uploadFile = $uploadDir . basename($_FILES['profile_picture']['name']);

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
            $profilePicture = $uploadFile;
        } else {
            echo "Error uploading the file.";
            exit();
        }
    }

    // Update user profile
    $sql = "UPDATE users SET name='$name', email='$email'" . ($profilePicture ? ", profile_picture='$profilePicture'" : "") . " WHERE id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php?update=success");
        exit();
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $conn->close();
}
