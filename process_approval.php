<?php
include 'connection.php'; // Include your database connection file
session_start(); // Start session to manage admin login

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['provider_id'], $_POST['action'])) {
    $provider_id = $_POST['provider_id'];
    $action = $_POST['action'];

    // Update provider status based on action
    if ($action === 'approve') {
        $sql = "UPDATE service_providers SET status = 'approved' WHERE id = ?";
    } elseif ($action === 'reject') {
        $sql = "UPDATE service_providers SET status = 'rejected' WHERE id = ?";
    } else {
        echo "Invalid action.";
        exit;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $provider_id);
    
    if ($stmt->execute()) {
        echo "Provider status updated successfully.";
        // Optionally, you can redirect to approved.php or show a success message
    } else {
        echo "Error updating provider status: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
