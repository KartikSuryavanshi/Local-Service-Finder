<?php
session_start(); // Start the session
include 'connection.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'], $_POST['password'], $_POST['role'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role']; // To distinguish between user and provider sign-in

        if ($role === 'user') {
            // Check if the user exists in the users table
            $sql = "SELECT id, username, password FROM users WHERE email = ?";
        } elseif ($role === 'provider') {
            // Check if the provider exists in the service_providers table
            $sql = "SELECT id, provider_name, password, approved FROM service_providers WHERE email = ?";
        } else {
            echo "<script>alert('Invalid role selected.');</script>";
            exit();
        }

        // Prepare a statement to prevent SQL injection
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            if ($role === 'provider') {
                $stmt->bind_result($id, $name, $hashed_password, $approved);
            } else {
                $stmt->bind_result($id, $name, $hashed_password);
            }
            $stmt->fetch();

            // Verify the entered password with the hashed password
            if (password_verify($password, $hashed_password)) {
                // Check approval status for providers
                if ($role === 'provider' && $approved !== 'approved') {
                    $message = ($approved === 'pending') ? "Your request is pending approval." : "Your request was rejected.";
                    echo "<script>alert('$message'); window.location.href = 'signin.php';</script>";
                    exit();
                }

                // Password is correct, start session and store details
                $_SESSION['user_id'] = $id;
                $_SESSION['name'] = $name; // Store user/provider name here
                $_SESSION['email'] = $email; // Store email in session
                $_SESSION['role'] = $role;

                // Redirect based on role
                if ($role === 'user') {
                    header("Location: sidebar.php");
                } else {
                    header("Location: provider_dashboard.php");
                }
                exit();
            } else {
                echo "<script>alert('Incorrect password.');</script>";
            }
        } else {
            echo "<script>alert('No account found with that email.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Local Service Finder</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            position: relative;
            overflow: hidden;
            height: 100vh; /* Set body height to full viewport height */
            display: flex; /* Use flexbox to center content */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
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
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); /* Keep some shadow for depth */
            background: rgba(255, 255, 255, 0.2); /* Slightly more transparent */
            border: 1px solid rgba(255, 255, 255, 0.4); /* Lighter border for better effect */
            margin-top: 20px; /* Remove margin-top for vertical centering */
            max-height: 90vh;
            overflow-y: auto; /* Change scroll from scroll to auto */
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: #fff;
        }
        .header h1 {
            font-size: 2.5em;
        }
        .form-toggle {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-toggle button {
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-toggle button:hover {
            background-color: #5dacbd;
        }
        .form-container {
            display: none;
        }
        .form-box {
            background: rgba(255, 255, 255, 0.3); /* Semi-transparent white background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .form-box input[type="email"], .form-box input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
        }
        .form-box .submit-btn {
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-box .submit-btn:hover {
            background-color: #5dacbd;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Background video -->
    <video autoplay muted loop>
        <source src="../admin/img/videoplayback.mp4.crdownload" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="container">
        <div class="header">
            <h1>Sign In</h1>
        </div>
        <div class="form-toggle">
            <button id="userSignInBtn" onclick="showForm('user')">Sign In as User</button>
            <button id="providerSignInBtn" onclick="showForm('provider')">Sign In as Service Provider</button>
            <button id="signupBtn" onclick="window.location.href='signup.php';">Sign Up</button> <!-- Redirect to signup.php -->
        </div>
         
        <div id="userForm" class="form-container">
            <div class="form-box">
                <h2>User Sign In</h2>
                <form action="signin.php" method="post">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    
                    <input type="hidden" name="role" value="user">

                    <button type="submit" class="submit-btn">Sign In as User</button>
                </form>
            </div>
        </div>

        <div id="providerForm" class="form-container">
            <div class="form-box">
                <h2>Service Provider Sign In</h2>
                <form action="signin.php" method="post">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    
                    <input type="hidden" name="role" value="provider">

                    <button type="submit" class="submit-btn">Sign In as Provider</button>
                </form>
            </div>
        </div>

        <div id="signupForm" class="form-container">
            <div class="form-box">
                <h2>Sign Up</h2>
                <form action="signup.php" method="post">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <label for="role">Role:</label>
                    <select id="role" name="role" required>
                        <option value="user">User</option>
                        <option value="provider">Service Provider</option>
                    </select>

                    <button type="submit" class="submit-btn">Sign Up</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showForm(role) {
            document.getElementById("userForm").style.display = role === 'user' ? 'block' : 'none';
            document.getElementById("providerForm").style.display = role === 'provider' ? 'block' : 'none';
        }
    </script>
</body>
</html>
