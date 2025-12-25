<?php
session_start();
include 'connection.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Check if booking_id is set
if (isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    // Prepare a delete statement
    $sql = "DELETE FROM bookings WHERE id = ?"; // Assuming user_id is stored in the bookings table
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bind_param("ii", $booking_id, $_SESSION['id']);
    
    if ($stmt->execute()) {
        // Redirect back to user_bookings.php with a success message
        header("Location: user_bookings.php?message=Booking cancelled successfully.");
        exit();
    } else {
        // Handle error
        header("Location: user_bookings.php?error=Error cancelling booking.");
        exit();
    }
} else {
    // If booking_id is not set, redirect to bookings page with an error
    header("Location: user_bookings.php?error=No booking selected to cancel.");
    exit();
}

$stmt->close();
$conn->close();
?>
