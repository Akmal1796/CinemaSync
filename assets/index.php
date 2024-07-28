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
  <title>TVFlix</title>
  <link rel="stylesheet" href="Styles/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }

    .dropdown-menu a,
    .dropdown-menu button {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      background: none;
      border: none;
      width: 100%;
      text-align: left;
      cursor: pointer;
    }

    .dropdown-menu a:hover,
    .dropdown-menu button:hover {
      background-color: #f1f1f1;
    }
  </style>
</head>

<body>
  <div class="main">
    <nav class="nav-bar">
      <div class="navbar">
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
      </div>
    </nav>
  </div>

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