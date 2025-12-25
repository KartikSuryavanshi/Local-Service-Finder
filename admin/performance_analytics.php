<?php
session_start(); // Start the session

// Check if the provider is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to view performance analytics.'); window.location.href = '../login.php';</script>";
    exit();
}

$email = $_SESSION['email']; // Get the provider's email from the session

// Include the connection file
include 'connection.php';

// Query to count distinct users who have added this provider to their favorites using user_id
$sql = "SELECT COUNT(DISTINCT user_id) AS favorite_count FROM heart WHERE email = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($favorite_count);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "<p>Error preparing statement: " . $conn->error . "</p>";
    exit();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Analytics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1e7f6; /* Light purple background */            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .analytics-container {
            max-width: 300px; /* Reduced width */
            margin: auto;
            background: #fff;
            padding: 15px; /* Reduced padding */
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .favorite-count {
            font-size: 36px; /* Reduced font size */
            color: #28a745;
            text-align: center;
        }
        .label {
            font-size: 16px; /* Reduced font size */
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Performance Analytics</h1>
    <div class="analytics-container">
        <p class="favorite-count"><?php echo $favorite_count; ?></p>
        <p class="label">Unique users have added you to their favorites</p>
    </div>
</body>
</html>
