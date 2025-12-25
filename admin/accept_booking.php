<?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to manage bookings.'); window.location.href = '../login.php';</script>";
    exit();
}

if (isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    // Update the booking status to 'accepted'
    $sql = "UPDATE bookings SET status = 'accepted' WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        // Redirect back to the manage bookings page after accepting the booking
        echo "<script>alert('Booking accepted successfully.'); window.location.href = 'manage_bookings.php';</script>";
    } else {
        echo "<script>alert('Error accepting booking. Please try again later.'); window.location.href = 'location_settings.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'location_settings.php';</script>";
}

$conn->close();
?>
