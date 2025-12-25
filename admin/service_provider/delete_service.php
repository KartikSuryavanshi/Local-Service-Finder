<?php
session_start();

$servername = 'localhost';
$username = 'root';
$password = '';
$db = 'local';
$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['provider_id'])) {
    header('Location: your_service.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['id'];

    $sql = "DELETE FROM services WHERE id = ? AND provider_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $service_id, $_SESSION['provider_id']);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header('Location: provider_dashboard.php');
exit;
?>
