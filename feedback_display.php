<?php
include 'connection.php'; // Include your database connection file

// Session or authentication to identify the service provider
session_start();
$service_provider_email = $_SESSION['email']; // Ensure session handling is implemented

// Fetch feedback for the logged-in service provider
$stmt = $conn->prepare("SELECT user_email, rating, description, feedback_time FROM feedback WHERE service_provider_email = ?");
$stmt->bind_param("s", $service_provider_email);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback Received</title>
    <link rel="stylesheet" href="path/to/your/premium.css"> <!-- Link to your high-level paid CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1e7f6; /* Light purple background */            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            color: #333;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .feedback-container {
            margin: 50px auto;
            padding: 20px;
            max-width: 900px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .feedback-header {
            font-size: 28px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .feedback-card {
            background: #f1f1f1;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .feedback-info {
            font-size: 16px;
            margin-bottom: 10px;
            color: #444;
        }

        .rating {
            color: #FFD700; /* Gold color for stars */
            font-weight: bold;
        }

        .description {
            font-size: 15px;
            color: #555;
            margin-bottom: 10px;
        }

        .feedback-time {
            font-size: 13px;
            color: #777;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="feedback-container">
    <h2 class="feedback-header">Feedback Received</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="feedback-card">
                <div class="feedback-info">
                    <strong>From:</strong> <?= htmlspecialchars($row['user_email']) ?>
                    <span class="rating">| Rating: <?= htmlspecialchars($row['rating']) ?> / 5</span>
                </div>
                <div class="description">
                    <strong>Description:</strong> <?= nl2br(htmlspecialchars($row['description'])) ?>
                </div>
                <div class="feedback-time">
                    <strong>Time:</strong> <?= htmlspecialchars($row['feedback_time']) ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No feedback found.</p>
    <?php endif; ?>
</div>

</body>
</html>
