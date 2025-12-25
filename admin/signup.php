<?php
include 'connection.php'; // Include your database connection file
session_start(); // Start session to manage user/provider login

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle Service Provider Signup
    if (isset($_POST['providername'], $_POST['email'], $_POST['password'], $_POST['confirm_password']) && isset($_FILES['profile_pic'])) {
        $providername = $_POST['providername'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate passwords match
        if ($password !== $confirm_password) {
            echo "Passwords do not match.";
            exit;
        }

        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Handle profile picture upload
        if ($_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir . basename($_FILES['profile_pic']['name']);

            // Create uploads directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Validate file type (ensure it’s an image)
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($_FILES['profile_pic']['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                echo "Only JPG, PNG, and GIF images are allowed.";
                exit;
            }

            // Move uploaded file
            if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadFile)) {
                echo "Sorry, there was an error uploading your file.";
                exit;
            }
        } else {
            echo "File upload error.";
            exit;
        }

        // Handle Aadhar card upload
if ($_FILES['aadhar_card']['error'] == UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/'; // Same directory or a different one
    $uploadFileAadhar = $uploadDir . basename($_FILES['aadhar_card']['name']);

    // Validate file type (ensure it’s an image)
    $fileTypeAadhar = mime_content_type($_FILES['aadhar_card']['tmp_name']);
    if (!in_array($fileTypeAadhar, $allowedTypes)) {
        echo "Only JPG, PNG, and GIF images are allowed for Aadhar card.";
        exit;
    }

    // Move uploaded file
    if (!move_uploaded_file($_FILES['aadhar_card']['tmp_name'], $uploadFileAadhar)) {
        echo "Sorry, there was an error uploading your Aadhar card.";
        exit;
    }
} else {
    echo "Aadhar card upload error.";
    exit;
}




        // Insert provider data into the database
        $sql = "INSERT INTO service_providers (provider_name, email, password, profile_picture, aadhar_card) VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("sssss", $providername, $email, $hashed_password, $uploadFile, $uploadFileAadhar);
        if ($stmt->execute()) {
            echo "Service provider registered successfully.";
            $_SESSION['provider_email'] = $email; // Save provider email in session

            // Redirect to provider dashboard
            header("Location: provider_dashboard.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } 
    // Handle User Signup
    elseif (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate passwords match
        if ($password !== $confirm_password) {
            echo "Passwords do not match.";
            exit;
        }

        // Hash the password securely
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
            $_SESSION['user_email'] = $email; // Save user email in session

            // Redirect to user dashboard
            header("Location: sidebar.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } 
    else {
        echo "Required form data or file is missing.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Local Service Finder</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative; /* Added to position video */
            overflow: hidden; /* Hide overflow */
        }

        video {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1; /* Ensure video is behind content */
            object-fit: cover;
            opacity: 0.9; /* To make content easily visible */
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
            margin-top: 20px;
            max-height: 90vh;
            overflow-y: scroll;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #ffffff;
            font-size: 2em;
        }

        .form-toggle {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .form-toggle button {
            background-color: #150734;
            color: #fff;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .form-toggle button:hover {
            background-color: #4b9fe1;
        }

        .form-box {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .form-box input[type="text"],
        .form-box input[type="email"],
        .form-box input[type="password"],
        .form-box input[type="file"] {
            width: 95%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 50px 7px;
            border: 1px solid #ddd;
            font-size: 1em;
        }

        .form-box input:focus {
            border-color: #4b9fe1;
            outline: none;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .form-box .submit-btn {
            background-color: #150734;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 1.1em;
        }

        .form-box .submit-btn:hover {
            background-color: #4b9fe1;
        }

        .form-container {
            display: none;
        }

        #userForm {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Background video -->
    <video autoplay muted loop>
        <source src="../admin/img/videoplayback.mp4.crdownload" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sign Up / Sign In</h1>
        </div>
        <div class="form-toggle">
            <button id="userBtn" onclick="showForm('user')">User Sign Up</button>
            <button id="providerBtn" onclick="showForm('provider')">Service Provider Sign Up</button>
            <button id="signinBtn" onclick="window.location.href='signin.php';">Sign In</button>

        </div>

        <!-- User Form -->
        <div id="userForm" class="form-container">
            <div class="form-box">
                <h2>User Sign Up</h2>
                <form action="signup.php" method="post">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" name="confirm_password" required>

                    <button type="submit" class="submit-btn">Sign Up as User</button>
                </form>
            </div>
        </div>

        <!-- Provider Form -->
        <div id="providerForm" class="form-container">
            <div class="form-box">
                <h2>Service Provider Sign Up</h2>
                <form action="signup.php" method="post" enctype="multipart/form-data">
                    <label for="providername">Provider Name:</label>
                    <input type="text" id="providername" name="providername" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" name="confirm_password" required>

                    <label for="profile-pic">Profile Picture:</label>
                    <input type="file" id="profile-pic" name="profile_pic" accept="image/*" required>


            <label for="aadhar-card">Your Aadhar Card:</label>
            <input type="file" id="aadhar-card" name="aadhar_card" accept="image/*" required>



                    <button type="submit" class="submit-btn">Sign Up as Provider</button>
                </form>
            </div>
        </div>

        <!-- Sign In Form -->
        <div id="signinForm" class="form-container">
            <div class="form-box">
                <h2>Sign In</h2>
                <form action="signin.php" method="post">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <a href="signin.php"><button id="signinBtn" type="button">Sign In</button></a>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showForm(form) {
            document.getElementById('userForm').style.display = 'none';
            document.getElementById('providerForm').style.display = 'none';
            document.getElementById('signinForm').style.display = 'none';

            if (form === 'user') {
                document.getElementById('userForm').style.display = 'block';
            } else if (form === 'provider') {
                document.getElementById('providerForm').style.display = 'block';
            } else if (form === 'signin') {
                document.getElementById('signinForm').style.display = 'block';
            }
        }
    </script>
</body>
</html>
