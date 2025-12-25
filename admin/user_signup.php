<?php

include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form data for user
    if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate form fields
        if ($password !== $confirm_password) {
            echo "Passwords do not match.";
            exit;
        }

        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "User registered successfully.";
            // Redirect to user dashboard or relevant page
            header("Location: sidebar.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Form data is missing.";
    }
} else {
   
}
?>
