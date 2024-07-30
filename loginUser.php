<?php
session_start();
require_once './conn.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['email']) && isset($input['pwd'])) {
        $email = $input['email'];
        $password = $input['pwd'];

        // Prepare and execute a query to fetch user data including the user ID
        $sql = "SELECT `id`, `password`, `name` FROM users WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row["id"]; // Retrieve user ID from the fetched row
            $hashedPassword = $row["password"];

            // Verify the provided password against the hashed password
            if (password_verify($password, $hashedPassword)) {
                // Successful login
                $name = $row["name"];
                $_SESSION['User_ID'] = $user_id; // Set user ID in session
                $_SESSION['Email'] = $email; // Set user email in session
                $_SESSION['UserName'] = $name; // Set user name in session

                http_response_code(200);
                echo json_encode(['message' => 'Login successful']);
            } else {
                // Invalid password
                http_response_code(401);
                echo json_encode(['message' => 'Invalid email or password']);
            }
        } else {
            // User not found
            http_response_code(404);
            echo json_encode(['message' => 'User not found']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid JSON data']);
    }

    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Invalid request method']);
}
