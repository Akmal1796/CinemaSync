<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tvflix";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize profile image path
$profileImagePath = "Styles/images/profile.png"; // default profile image

// Fetch profile image if user is logged in
if (isset($_SESSION['User_ID'])) {
    $uid = $_SESSION['User_ID'];
    echo "<script>console.log('Session id: " . $uid . "');</script>"; // Debug output

    $sql = "SELECT profile_picture FROM users WHERE id = '$uid'";
    $result = $conn->query($sql);

    if ($result === false) {
        // Debug output for SQL error
        echo "<script>console.log('SQL Error: " . $conn->error . "');</script>";
    } else if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (!empty($row['profile_picture'])) {
            $profileImagePath = $row['profile_picture'];
        } else {
            // Debug output
            echo "<script>console.log('Profile picture is empty');</script>";
        }
    } else {
        // Debug output
        echo "<script>console.log('No user found with id: " . $uid . "');</script>";
    }
} else {
    // Debug output
    echo "<script>console.log('No session id found');</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary meta tags -->
    <meta name="description" content="CinemaSync is a popular app made by Mohamed Akmal">

    <!-- Favicon -->
    <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">

    <!-- Google font Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">

    <!-- Custom CSS Link -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <style>
        .profile-container {
            position: relative;
            display: inline-block;
        }

        #profile-icon {
            width: 50px;
            height: 50px;
            cursor: pointer;
            border-radius: 50%;
            object-fit: cover;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 60px;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            border-radius: 10px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1000000;
        }

        .dropdown-menu a,
        .dropdown-menu button {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            background: none;
            border: none;
            border-radius: 10px;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background-color: #f1f1f1;
        }
    </style>

    <!-- Custom JS Link -->
    <script src="./assets/js/global.js" defer></script>
    <script src="./assets/js/movie-list.js" type="module"></script>
</head>

<body>

    <!-- HEADER -->
    <header class="header">

        <a href="./index.php" class="logo">
            <img src="./assets/images/logo.svg" width="140" height="32" alt="CinemaSync home">
        </a>

        <div class="search-box" search-box>
            <div class="search-wrapper" search-wrapper>
                <input type="text" name="search" aria-label="search movies" placeholder="Search any movies..." class="search-field" autocomplete="off" search-field>

                <img src="./assets/images/search.png" width="24" height="24" alt="search" class="leading-icon">
            </div>

            <button class="search-btn" search-toggler>
                <img src="./assets//images/close.png" width="24" height="24" alt="close search box">
            </button>
        </div>

        <div class="profile-container">
            <img id="profile-icon" src="<?php echo htmlspecialchars($profileImagePath); ?>" alt="User Icon">
            <div id="dropdown-menu" class="dropdown-menu">
                <?php
                if (isset($_SESSION['UserName'])) {
                    echo
                    '<a href="profile_edit.php"><button type="button" class="logreg">' . $_SESSION['UserName'] . '</button></a>' .
                        '<a href="logout.php"><button type="button" class="logreg">Logout</button></a>';
                } else {
                    echo
                    '<a href="login.html"><button type="button" class="logreg">Login</button></a>' .
                        '<a href="login.html"><button type="button" class="logreg">Register</button></a>';
                }
                ?>
            </div>
        </div>

        <button class="search-btn" search-toggler menu-close>
            <img src="./assets//images/search.png" width="24" height="24" alt="open search box">
        </button>

        <button class="menu-btn" menu-btn menu-toggler>
            <img src="./assets/images/menu.png" width="24" height="24" alt="open menu" class="menu">
            <img src="./assets/images/menu-close.png" width="24" height="24" alt="close menu" class="close">
        </button>

    </header>

    <main>

        <!-- SIDEBAR -->
        <nav class="sidebar" sidebar></nav>

        <div class="overlay" overlay menu-toggler></div>


        <article class="container" page-content></article>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');

            profileIcon.addEventListener('click', function(event) {
                event.stopPropagation();
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            });

            window.addEventListener('click', function(event) {
                if (!event.target.closest('.profile-container')) {
                    dropdownMenu.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>