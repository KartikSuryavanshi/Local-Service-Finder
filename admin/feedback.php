<?php
include 'connection.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_provider_email = $_POST['service_provider_email'];
    $user_email = $_POST['user_email'];
    $rating = $_POST['rating'];
    $description = $_POST['description'];

    // Insert feedback into the database using prepared statements
    $stmt = $conn->prepare("INSERT INTO feedback (service_provider_email, user_email, rating, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $service_provider_email, $user_email, $rating, $description);

    if ($stmt->execute()) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href = 'feedback.php';</script>";
    } else {
        echo "<script>alert('Failed to submit feedback. Please try again.'); window.location.href = 'feedback.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Feedback</title>
    <style>
        /* Add your professional styling here */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 50px;
            background: linear-gradient(135deg, #f0f2f5, #cfd9df);
            color: #333;
        }

        form {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1), 0 5px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease-in-out;
        }

        form:hover {
            transform: scale(1.02);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0 20px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            font-size: 14px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #4CAF50;
        }

        button {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        button:hover {
            background-color: #45a049;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .view-feedback-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 50px;
            background: linear-gradient(135deg, #8E2DE2, #4A00E0); /* Blue and Purple gradient */
            color: #fff;
        }

        form {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1), 0 5px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease-in-out;
        }

        form:hover {
            transform: scale(1.02);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4A00E0; /* Purple color for labels */
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0 20px 0;
            border-radius: 8px;
            border: 1px solid #ddd;
            background-color: #f4f4f4;
            font-size: 14px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #8E2DE2; /* Focus border color - Purple */
        }

        button {
            background-color: #8E2DE2; /* Purple button */
            color: #ffffff;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        button:hover {
            background-color: #6A1B9A; /* Darker purple on hover */
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #fff; /* White color for heading */
        }

        .view-feedback-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .view-feedback-btn-link button {
            background-color: #4A00E0; /* Blue button for View Feedback */
            color: #fff;
        }

        .view-feedback-btn-link button:hover {
            background-color: #8E2DE2; /* Purple color on hover */
        }
    </style>
</head>
<body>

<h2>Submit Feedback for a Service Provider</h2>
<form method="post" action="feedback.php">
    <label for="service_provider_email">Service Provider Email:</label>
    <input type="email" id="service_provider_email" name="service_provider_email" required>

    <label for="user_email">Your Email:</label>
    <input type="email" id="user_email" name="user_email" required>

    <label for="rating">Rating (1-5):</label>
    <select id="rating" name="rating" required>
        <option value="1">1 - Poor</option>
        <option value="2">2 - Fair</option>
        <option value="3">3 - Good</option>
        <option value="4">4 - Very Good</option>
        <option value="5">5 - Excellent</option>
    </select>

    <label for="description">Description:</label>
    <textarea id="description" name="description" rows="5" required></textarea>

    <button type="submit">Submit Feedback</button>
</form>

<!-- View Feedback Button -->
<div class="view-feedback-btn">
    <a href="view_feedback.php" class="view-feedback-btn-link">
        <button type="button">View Feedback</button>
    </a>
</div>

</body>
</html>
