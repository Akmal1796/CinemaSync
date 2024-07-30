<?php

require_once "./conn.php";
header('Content-Type: application/json'); // Set content type to JSON

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if JSON decoding was successful
    if (is_null($data)) {
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Invalid JSON data"]);
        exit();
    }

    // Check if required fields are present
    if (!isset($data["uid"]) || !isset($data["email"]) || !isset($data["pwd"]) || !isset($data["pwdrepeat"])) {
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Missing required fields"]);
        exit();
    }

    // Prepare and sanitize form data
    $name = mysqli_real_escape_string($conn, $data["uid"]);
    $email = mysqli_real_escape_string($conn, $data["email"]);
    $password = mysqli_real_escape_string($conn, $data["pwd"]);
    $passwordRepeat = mysqli_real_escape_string($conn, $data["pwdrepeat"]);

    // Directory to save the uploaded profile images
    $targetDir = "uploads/profile_pictures/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetFile = $targetDir . "profile.png"; // Default profile image

    // Validate password match
    if ($password === $passwordRepeat) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if the email already exists
        $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($checkEmailQuery);

        if ($result->num_rows > 0) {
            // Email already exists
            http_response_code(409); // Conflict
            echo json_encode(["message" => "Email already exists"]);
        } else {
            // Insert data into the database
            $sql = "INSERT INTO `users`(`name`, `email`, `password`, `profile_picture`) VALUES ('$name', '$email', '$hashedPassword', '$targetFile')";

            if ($conn->query($sql) === TRUE) {
                http_response_code(201); // Created
                echo json_encode(["message" => "User registered successfully"]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["message" => "Error: " . $sql . "<br>" . $conn->error]);
            }
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Passwords do not match."]);
    }

    $conn->close();
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["message" => "Invalid request method."]);
}
