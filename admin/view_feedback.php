<?php
include 'connection.php'; // Include your database connection file

// Assuming the user is logged in and their email is stored in the session
session_start();
$user_email = $_SESSION['email']; // Replace with the correct session variable for user authentication

// Fetch feedback submitted by the user
$stmt = $conn->prepare("SELECT service_provider_email, rating, description, feedback_time FROM feedback WHERE user_email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Submitted Feedback</title>
    <link rel="stylesheet" href="path/to/your/professional.css"> <!-- Link to your premium CSS file -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }

        .feedback-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .feedback-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .feedback-card:hover {
            transform: scale(1.02);
        }

        .feedback-header {
            color: #4CAF50;
            font-size: 24px;
            margin-bottom: 15px;
            text-align: center;
        }

        .feedback-details {
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .feedback-time {
            font-size: 12px;
            color: #888;
            text-align: right;
        }

        .rating {
            color: #FFD700;
            font-weight: bold;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #8E2DE2, #4A00E0); /* Blue and Purple gradient */
            color: #fff;
            padding: 20px;
        }

        .feedback-container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1), 0 5px 10px rgba(0, 0, 0, 0.05);
        }

        h2.feedback-header {
            color: #4A00E0; /* Purple header */
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .feedback-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .feedback-card:hover {
            transform: scale(1.02);
        }

        .feedback-details {
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .feedback-time {
            font-size: 12px;
            color: #888;
            text-align: right;
        }

        .rating {
            color: #FFD700;
            font-weight: bold;
        }

        /* Blue and Purple color accents */
        .feedback-header {
            color: #4A00E0; /* Purple for header */
        }

        .rating {
            color: #FFD700;
        }

        .feedback-details strong {
            color: #4A00E0; /* Purple for strong text */
        }

        .feedback-card {
            border: 1px solid #4A00E0; /* Purple border around feedback cards */
        }

        .feedback-time {
            color: #4A00E0; /* Purple color for time */
        }

    </style>
</head>
<body>

<div class="feedback-container">
    <h2 class="feedback-header">My Submitted Feedback</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="feedback-card">
                <div class="feedback-details">
                    <strong>Service Provider Email:</strong> <?= htmlspecialchars($row['service_provider_email']) ?><br>
                    <span class="rating"><strong>Rating:</strong> <?= htmlspecialchars($row['rating']) ?> / 5</span><br>
                    <strong>Description:</strong> <?= nl2br(htmlspecialchars($row['description'])) ?>
                </div>
                <div class="feedback-time">
                    <strong>Submitted On:</strong> <?= htmlspecialchars($row['feedback_time']) ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You haven't submitted any feedback yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
