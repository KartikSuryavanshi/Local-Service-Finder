<?php
include 'connection.php';

// Setting PHP mail configurations
ini_set('SMTP', 'smtp.yourdomain.com'); // Replace with your SMTP server
ini_set('smtp_port', '25'); // Replace with your SMTP port if different
ini_set('sendmail_from', 'no-reply@yourdomain.com'); // Replace with your sender email

if (isset($_POST['booking_id'])) {
    // Use prepared statements to prevent SQL injection
    $booking_id = $_POST['booking_id'];
    $otp = rand(1000, 9999); // Generate a 4-digit OTP

    // Update the bookings table to save the OTP
    $update_sql = $conn->prepare("UPDATE bookings SET otp = ? WHERE booking_id = ?");
    $update_sql->bind_param("ii", $otp, $booking_id);

    if ($update_sql->execute()) {
        // Query to get the user's email using a prepared statement
        $select_sql = $conn->prepare("SELECT u.email FROM bookings b JOIN users u ON b.user_email = u.email WHERE b.booking_id = ?");
        $select_sql->bind_param("i", $booking_id);
        $select_sql->execute();
        $result = $select_sql->get_result();

        if ($result && $result->num_rows > 0) {
            $user_email = $result->fetch_assoc()['email'];

            // Send OTP to the user's email
            $subject = "Your Service Completion OTP";
            $message = "Your OTP is: $otp";
            $headers = "From: no-reply@yourdomain.com\r\n";
            $headers .= "Reply-To: support@yourdomain.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8";

            if (mail($user_email, $subject, $message, $headers)) {
                echo "<script>alert('OTP sent to the user. Please verify it to complete the booking.'); window.location.href = 'verify_otp.php?booking_id=$booking_id';</script>";
            } else {
                echo "<script>alert('Failed to send OTP. Please try again later.'); window.location.href = 'location_settings.php';</script>";
            }
        } else {
            echo "<script>alert('User email not found. Please try again later.'); window.location.href = 'location_settings.php';</script>";
        }

        $select_sql->close();
    } else {
        echo "<script>alert('Failed to update OTP in the database. Please try again.'); window.location.href = 'location_settings.php';</script>";
    }

    $update_sql->close();
    $conn->close();
} else {
    echo "<script>alert('Booking ID is missing. Please try again.'); window.location.href = 'location_settings.php';</script>";
}
?>
