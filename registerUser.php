<?php

require_once "./conn.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {

    // Prepare and sanitize form data
    $name = mysqli_real_escape_string($conn, $_POST["uid"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["pwd"]);
    $passwordRepeat = mysqli_real_escape_string($conn, $_POST["pwdrepeat"]);
    $profileImage = $_FILES["profile-image-input"];

    // Directory to save the uploaded profile images
    $targetDir = "uploads/profile_pictures/";
    // Create the directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $targetFile = $targetDir . basename($profileImage["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate password match
    if ($password === $passwordRepeat) {
        // Validate and upload the image
        $check = getimagesize($profileImage["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($profileImage["tmp_name"], $targetFile)) {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert data into the database
                $sql = "INSERT INTO `users`(`name`, `email`, `password`, `profile_picture`) VALUES ('$name', '$email', '$hashedPassword', '$targetFile')";

                if ($conn->query($sql) === TRUE) {
                    header("Location: success.html");
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        echo "Passwords do not match.";
    }

    $conn->close();
}
