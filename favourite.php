<?php
session_start();
include 'connection.php'; // Include database connection

// Retrieve unique favorite providers by email from the heart table for the current user
$user_id = $_SESSION['user_id']; // Assuming user ID is stored in session after login
$sql = "SELECT DISTINCT email, provider_name, profile_picture FROM heart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Providers</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #4a90e2, #9013fe);
            color: #fff;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #f39c12;
        }
        .provider-card {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .provider-card img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }
        .provider-info {
            display: flex;
            flex-direction: column;
        }
        .provider-info h2 {
            font-size: 1.5em;
            margin: 0;
            color: #333;
        }
        .provider-info p {
            color: #666;
            margin: 5px 0;
        }
         /* General Styling */
         body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #e0aaff, #b3c6ff); /* Light purple to light blue gradient */
            color: #fff;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9); /* Light, semi-transparent container */
            border-radius: 15px; /* Rounded corners */
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            color: #7ed5ea; /* Light blue for heading */
            margin-bottom: 30px;
            font-size: 2.5em;
        }

        .provider-card {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.85); /* Slightly transparent white background */
            color: #333;
            border-radius: 12px;
            margin-bottom: 25px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .provider-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.3); /* Enhanced shadow on hover */
        }

        .provider-card img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }

        .provider-info {
            display: flex;
            flex-direction: column;
        }

        .provider-info h2 {
            font-size: 1.6em;
            margin: 0;
            color: #5dacbd; /* Teal color for provider name */
        }

        .provider-info p {
            color: #666;
            margin: 5px 0;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .provider-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .provider-card img {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Favorite Providers</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="provider-card">
                    <img src="<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="Profile Photo">
                    <div class="provider-info">
                        <h2><?php echo htmlspecialchars($row['provider_name']); ?></h2>
                        <p>Email: <?php echo htmlspecialchars($row['email']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No favorite providers found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
