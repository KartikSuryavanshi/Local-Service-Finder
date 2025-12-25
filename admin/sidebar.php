<?php
include 'connection.php';
include 'user_signup.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Local Service Finder</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
  <div class="container add" id="container">
    <div class="brand">
      <h3>Service Finder</h3>
      <a href="#" id="toggle"><i class="bi bi-list"></i></a>
    </div>
    <div class="user">
      <img src="img/img1.jpg" alt="User Profile">
      <div class="name">
        <h5>Welcome, User</h5>
      </div>
    </div>
    <div class="navbar">
      <ul>
        <li><a href="#"><i class="bi bi-house"></i><span>Home</span></a></li>
        <li><a href="provider.php"><i class="bi bi-person"></i><span>Service Providers</span></a></li>
        <li><a href="services.php"><i class="bi bi-geo-alt"></i><span>Nearby Services</span></a></li>
        <li><a href="feedback.php"><i class="bi bi-geo-alt"></i><span>FeedBack</span></a></li>
        <li><a href="message.php"><i class="bi bi-chat-dots"></i><span>Messages</span></a></li>
        <li><a href="user_bookings.php"><i class="bi bi-calendar-check"></i><span>Bookings</span></a></li>
   
        <li><a href="favourite.php"><i class="bi bi-heart"></i><span>Favorites</span></a></li>
        <li><a href="#"><i class="bi bi-gear"></i><span>Settings</span></a></li>
        <li><a href="logout.php"><i class="bi bi-box-arrow-right"></i><span>Log Out</span></a></li>

      </ul>
    </div>
    
  </div>
  <script src="js/index.js"></script>
</body>
</html>
