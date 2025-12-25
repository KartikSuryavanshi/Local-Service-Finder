<?php
include 'connection.php'; // Include your database connection

$sql = "SELECT * FROM services ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h2>" . htmlspecialchars($row['service_title']) . "</h2>";
        echo "<p>" . htmlspecialchars($row['service_description']) . "</p>";
        echo "<p>Provided by: " . htmlspecialchars($row['contact_email']) . "</p>";
        echo "</div>";
    }
} else {
    echo "No services available.";
}
?>
