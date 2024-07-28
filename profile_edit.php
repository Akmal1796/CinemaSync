<?php
session_start();
require_once './conn.php';

// Initialize variables
$profileImagePath = "Styles/images/profile.png"; // default profile image
$name = "";
$email = "";

// Fetch user data if logged in
if (isset($_SESSION['User_ID'])) {
    $user_id = $_SESSION['User_ID'];
    $sql = "SELECT name, email, profile_picture FROM users WHERE id='$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name']);
        $email = htmlspecialchars($row['email']);
        $profileImagePath = htmlspecialchars($row['profile_picture']) ?: $profileImagePath;
    } else {
        echo "No user found";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="Styles/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }

        .profile-image-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 560px;
            border-radius: 0;
            margin-bottom: 20px;
        }

        .profile-image-container img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 20px;
        }

        .profile-image-container label {
            display: block;
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group input[type="file"] {
            padding: 0;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <form action="update_profile.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="profile-image-container">
                <img id="profile-image-preview" src="<?php echo $profileImagePath; ?>" alt="Profile Image">
                <label for="profile-image-input">Change Profile Image</label>
                <input type="file" id="profile-image-input" name="profile_picture" accept="image/*">
            </div>
            <button type="submit" name="update">Update Profile</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileImageInput = document.getElementById('profile-image-input');
            const profileImagePreview = document.getElementById('profile-image-preview');

            profileImageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profileImagePreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>

</html>