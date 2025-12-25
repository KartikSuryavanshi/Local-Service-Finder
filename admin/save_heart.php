<?php
session_start();
include 'connection.php';

$user_id = $_POST['user_id'];
$provider_name = $_POST['provider_name'];
$email = $_POST['email'];
$profile_picture = $_POST['profile_picture'];
$favorited = $_POST['favorited'] === 'true';

// Check if provider is already in the heart table
if ($favorited) {
    // Add to favorites if not already present
    $sql = "INSERT INTO heart (user_id, provider_name, email, profile_picture) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE provider_name = provider_name";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $provider_name, $email, $profile_picture);
    $stmt->execute();
} else {
    // Remove from favorites
    $sql = "DELETE FROM heart WHERE user_id = ? AND provider_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $provider_name);
    $stmt->execute();
}
?>
