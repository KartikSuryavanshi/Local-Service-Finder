<?php
include 'connection.php';
include 'provider_signup.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form fields are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate form fields
        if ($password !== $confirm_password) {
            echo "Passwords do not match.";
            exit;
        }

        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "User registered successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Form data is missing.";
    }
} else {
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Service Provider Dashboard</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
  <div class="container add" id="container">
    <div class="brand">
      <h3>Service Finder - Provider</h3>
      <a href="#" id="toggle"><i class="bi bi-list"></i></a>
    </div>
    <div class="user">
      <img src="img/img1.jpg" alt="Provider Profile">
      <div class="name">
        <h5>Welcome, Service Provider</h5>
      </div>
    </div>
    <div class="navbar">
      <ul>
        <li><a href="#"><i class="bi bi-house"></i><span>Dashboard</span></a></li>
        <li><a href="#"><i class="bi bi-person"></i><span>Your Services</span></a></li>
        <li><a href="#"><i class="bi bi-clipboard-data"></i><span>Manage Bookings</span></a></li>
        <li><a href="#"><i class="bi bi-geo-alt"></i><span>Location Settings</span></a></li>
        <li><a href="#"><i class="bi bi-chat-dots"></i><span>Messages</span></a></li>
        <li><a href="#"><i class="bi bi-bar-chart"></i><span>Performance Analytics</span></a></li>
        <li><a href="#"><i class="bi bi-gear"></i><span>Account Settings</span></a></li>
        <li><a href="#"><i class="bi bi-box-arrow-right"></i><span>Log Out</span></a></li>
      </ul>
    </div>
  </div>
  <script src="js/index.js"></script>
</body>
</html>
