<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "local_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT b.id, s.service_title, b.booking_date FROM bookings b 
        JOIN services s ON b.service_id = s.id WHERE b.user_id = '$user_id'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
</head>
<body>

    <h1>My Bookings</h1>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2>" . $row["service_title"] . "</h2>";
            echo "<p><strong>Booking Date:</strong> " . $row["booking_date"] . "</p>";
            echo "</div><br>";
        }
    } else {
        echo "No bookings found.";
    }

    $conn->close();
    ?>
</body>
</html>
