<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['email'])) { // Adjusted to use 'email'
    echo "<script>alert('Please log in to view your account details.'); window.location.href = '../login.php';</script>";
    exit();
}

$servername = "localhost"; // Default server name
$username = "root"; // Default username
$password = ""; // Default password (usually empty for local setups)
$database = "local"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email']; // Use 'email' from the session

// Fetch provider details including the profile picture using prepared statement
$sql = "SELECT provider_name, email, password, profile_picture FROM service_providers WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email); // Bind the email to the query (use 's' for string)
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['provider_name'];
    $email = $row['email'];
    $profile_picture = $row['profile_picture']; 
} else {
    echo "No account details found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1e7f6; /* Light purple background */            background-size: cover; /* Ensure the background covers the entire viewport */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.9); /* Slightly transparent background */
            padding: 40px; /* Increased padding for better spacing */
            border-radius: 15px; /* Slightly rounded corners */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2); /* Deeper shadow for a 3D effect */
            max-width: 450px; /* Increased max width */
            text-align: center;
            position: relative; /* Position relative for pseudo-elements */
        }

        .profile-photo {
            width: 120px; /* Consistent size */
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px; /* Space below the photo */
            border: 3px solid #5dacbd; /* Adding a border to the profile picture */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); /* Shadow for profile picture */
            position: absolute; /* Position absolute to place it above the container */
            top: -60px; /* Shift up */
            left: 50%;
            transform: translateX(-50%); /* Center horizontally */
        }

        h1 {
            color: #5dacbd;
            margin: 70px 0 20px; /* Increased top margin to avoid overlap with the profile picture */
            font-size: 26px; /* Increased font size */
        }

        p {
            margin: 8px 0; /* More spacing between paragraphs */
            font-size: 16px; /* Slightly larger font for better readability */
            color: #333; /* Darker text for contrast */
        }

        .password {
            color: #888; /* Optional: to indicate that password is hashed and not displayed */
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .container {
                width: 90%; /* Responsive width */
            }

            .profile-photo {
                width: 100px; /* Smaller profile picture for smaller screens */
                height: 100px;
            }

            h1 {
                font-size: 22px; /* Adjusted heading size */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-photo">
        <h1>Account Details</h1>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Password:</strong> <span class="password">********</span></p>
    </div>
</body>
</html>

<?php
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
