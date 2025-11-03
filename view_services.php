<?php
session_start();
if (!isset($_SESSION['provider_id'])) {
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

$provider_id = $_SESSION['provider_id'];

$sql = "SELECT * FROM services WHERE provider_id = '$provider_id'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Services</title>
</head>
<body>
    <h1>My Services</h1>
    <a href="provider_dashboard.php">Back to Dashboard</a><br><br>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2>" . $row["service_title"] . "</h2>";
            echo "<p>" . $row["service_description"] . "</p>";
            echo "<img src='uploads/" . $row["service_image"] . "' alt='Service Image' width='200'>";
            echo "<p>Email: " . $row["contact_email"] . "</p>";
            echo "<p>Phone: " . $row["contact_phone"] . "</p>";
            echo "</div><br>";
        }
    } else {
        echo "No services found.";
    }

    $conn->close();
    ?>
</body>
</html>
