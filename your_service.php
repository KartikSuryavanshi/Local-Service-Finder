<?php
session_start(); // Start the session

if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to add a service.'); window.location.href = '../login.php';</script>";
    exit();
}

$provider_email = $_SESSION['email']; // Get the provider's email from the session
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "local";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement to fetch services for the logged-in provider
$sql = "SELECT * FROM services WHERE provider_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $provider_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Services</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #B19CD9; /* Light purple background */
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Flexbox container for horizontally arranged cards */
        .service-container {
            display: flex;
            flex-wrap: nowrap; /* Ensure cards stay in one row */
            gap: 20px; /* Space between cards */
            justify-content: flex-start; /* Align items at the start */
            overflow-x: auto; /* Enable horizontal scrolling if cards overflow */
        }

        .service-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border: 1px solid #d6a7f9; /* Light purple border */
            width: 300px; /* Fixed width for each card */
            transition: all 0.3s ease-in-out;
        }

        .service-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); /* Shadow effect on hover */
            transform: translateY(-5px); /* Lift effect on hover */
        }

        .service-card h2 {
            margin-top: 0;
            color: #6a4fad; /* Light purple color */
        }

        .service-image {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .service-details {
            margin-top: 10px;
        }

        .service-details p {
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Your Services</h1>
    
    <div class="service-container">
        <?php
        if ($result->num_rows > 0) {
            // Loop through the results and display each service
            while ($row = $result->fetch_assoc()) {
                echo "<div class='service-card'>";
                echo "<h2>" . htmlspecialchars($row['service_title']) . "</h2>";
                echo "<img src='uploads/" . htmlspecialchars($row['service_image']) . "' class='service-image' alt='Service Image'>";
                echo "<div class='service-details'>";
                echo "<p><strong>Description:</strong> " . htmlspecialchars($row['service_description']) . "</p>";
                echo "<p><strong>Contact Email:</strong> " . htmlspecialchars($row['contact_email']) . "</p>";
                echo "<p><strong>Contact Phone:</strong> " . htmlspecialchars($row['contact_phone']) . "</p>";
                echo "</div></div>";
            }
        } else {
            echo "<p>No services found.</p>";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
