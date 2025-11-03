<?php
session_start();

include 'connection.php';

// Check if the session variable is set
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Access session variables safely
$userId = $_SESSION['user_id'];
$providerName = $_SESSION['provider_name'] ?? 'Service Provider'; // Use provider_name as fallback
$role = $_SESSION['role'] ?? 'unknown'; // Fallback to 'unknown' if not set
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
        <h5>Welcome, <?php echo htmlspecialchars($providerName); ?></h5> <!-- Ensure safe output -->
        
      </div>
    </div>
    <div class="navbar">
      <ul>
       
        <li><a href="../admin/service_provider/add_service.php"><i class="bi bi-person"></i><span>Add Service</span></a></li>
        <li><a href="../admin/service_provider/your_service.php"><i class="bi bi-clipboard-data"></i><span>Your Services</span></a></li>
        <li><a href="manage_bookings.php"><i class="bi bi-geo-alt"></i><span>Manage Bookings</span></a></li>
        <li><a href="location_settings.php"><i class="bi bi-geo-alt"></i><span>Location Settings</span></a></li>
        <li><a href="messages.php"><i class="bi bi-chat-dots"></i><span>Messages</span></a></li>
        <li><a href="performance_analytics.php"><i class="bi bi-bar-chart"></i><span>Performance Analytics</span></a></li>
        <li><a href="feedback_display.php"><i class="bi bi-bar-chart"></i><span>Check FeedBack</span></a></li>
        <li><a href="../admin/service_provider/account_display.php"><i class="bi bi-gear"></i><span>Account Settings</span></a></li>
        <li><a href="logout.php"><i class="bi bi-box-arrow-right"></i><span>Log Out</span></a></li>
      </ul>
    </div>
  </div>
  <script src="js/index.js"></script>
</body>
</html>
