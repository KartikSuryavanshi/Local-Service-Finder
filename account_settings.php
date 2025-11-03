<?php
session_start();
if (!isset($_SESSION['provider_id'])) {
    header("Location: login.php");
    exit();
}

include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $provider_id = $_SESSION['provider_id'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE service_providers SET email='$email', phone='$phone' WHERE id='$provider_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Account details updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
</head>
<body>
    <h1>Update Account Details</h1>
    <form action="account_settings.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        <label for="phone">Phone:</label>
        <input type="text" name="phone" required><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
