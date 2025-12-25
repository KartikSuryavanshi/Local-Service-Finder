<?php
include 'connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the posted data
    $service_id = intval($_POST['service_id']);
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));
    $payment_method = htmlspecialchars(trim($_POST['payment_method']));
    $special_requests = htmlspecialchars(trim($_POST['special_requests']));

    // Validate inputs (basic validation)
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($payment_method)) {
        echo "Please fill in all required fields.";
        exit;
    }

    // Insert booking into database
    $sql = "INSERT INTO bookings (service_id, user_name, user_email, user_phone, address, payment_method, special_requests) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $service_id, $name, $email, $phone, $address, $payment_method, $special_requests);

    if ($stmt->execute()) {
        // Booking successful
        echo "<div style='text-align: center; margin-top: 50px;'>";
        echo "<h1>Booking Successful!</h1>";
        echo "<p>Your booking has been confirmed. Thank you!</p>";
        echo "<p>You will be redirected shortly.</p>";
        echo "</div>";
        // Redirect to dashboard after 3 seconds
        header("refresh:3;url=sidebar.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
