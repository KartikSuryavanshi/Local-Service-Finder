<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form data
    if (isset($_POST['providername'], $_POST['email'], $_POST['password'], $_POST['confirm_password']) && isset($_FILES['profile_pic'])) {
        $providername = $_POST['providername'];
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

        // Handle file upload
        if ($_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($_FILES['profile_pic']['name']);

            // Check if upload directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadFile)) {
                echo "Sorry, there was an error uploading your file.";
                exit;
            }
        } else {
            echo "File upload error: " . $_FILES['profile_pic']['error'];
            exit;
        }

        // Prepare SQL statement for database
        $sql = "INSERT INTO service_providers (provider_name, email, password, profile_picture) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("ssss", $providername, $email, $hashed_password, $uploadFile);

        if ($stmt->execute()) {
            echo "Service provider registered successfully.";
            // Redirect to provider dashboard after successful registration
            header("Location: provider_dashboard.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Form data or file is missing.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Provider Sign Up</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #e0e0e0, #b0b0b0);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .header h1 {
            color: #5dacbd;
        }

        .form-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .form-box input[type="text"],
        .form-box input[type="email"],
        .form-box input[type="password"],
        .form-box input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1em;
        }

        .form-box .submit-btn {
            background-color: #333;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .form-box .submit-btn:hover {
            background-color: #5dacbd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Service Provider Sign Up</h1>
        </div>
        <div class="form-box">
            <form action="service_provider_signup.php" method="post" enctype="multipart/form-data">
                <label for="providername">Service Provider Name:</label>
                <input type="text" id="providername" name="providername" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm_password" required>

                <label for="profile-pic">Upload Profile Picture (mandatory):</label>
                <input type="file" id="profile-pic" name="profile_pic" accept="image/*" required>

                <button type="submit" class="submit-btn">Sign Up as Service Provider</button>
            </form>
        </div>
    </div>
</body>
</html>
