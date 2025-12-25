<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'connection.php';

$email = $_SESSION['email'];

$sql = "SELECT b.booking_id, b.user_name, b.user_email, b.user_phone, b.address, s.service_title, b.booking_date 
        FROM bookings b 
        JOIN services s ON b.service_id = s.service_id 
        WHERE s.provider_email = ? AND b.status = 'accepted'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 2em;
            margin-bottom: 30px;
        }

        .booking-container {
            max-width: 800px;
            margin: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .booking-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            transition: transform 0.2s ease;
        }

        .booking-card:hover {
            transform: scale(1.02);
        }

        .booking-info h2 {
            font-size: 1.2em;
            color: #007bff;
            margin: 0;
        }

        .booking-info p {
            margin: 5px 0;
            color: #555;
            font-size: 1em;
        }

        .booking-info p strong {
            color: #333;
        }

        .complete-button {
            align-self: flex-start;
            padding: 10px 15px;
            font-size: 1em;
            color: #fff;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .complete-button:hover {
            background-color: #218838;
        }

        .no-bookings {
            text-align: center;
            font-size: 1.2em;
            color: #777;
            margin-top: 50px;
        }
        body {
    font-family: Arial, sans-serif;
    background-color: #f1e7f6; /* Light purple background */
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #5f3b77; /* Darker purple for the title */
    font-size: 2em;
    margin-bottom: 30px;
}

.booking-container {
    max-width: 800px;
    margin: auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.booking-card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    transition: transform 0.2s ease;
}

.booking-card:hover {
    transform: scale(1.02);
}

.booking-info h2 {
    font-size: 1.2em;
    color: #7a4d96; /* Lighter purple for service titles */
    margin: 0;
}

.booking-info p {
    margin: 5px 0;
    color: #555;
    font-size: 1em;
}

.booking-info p strong {
    color: #5f3b77; /* Darker purple for labels */
}

.complete-button {
    align-self: flex-start;
    padding: 10px 15px;
    font-size: 1em;
    color: #fff;
    background-color: #9b59b6; /* Purple background for the button */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.complete-button:hover {
    background-color: #8e44ad; /* Darker purple for hover effect */
}

.no-bookings {
    text-align: center;
    font-size: 1.2em;
    color: #777;
    margin-top: 50px;
}

    </style>
</head>
<body>
    <h1>Accepted Bookings</h1>
    <div class="booking-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='booking-card'>";
                echo "<div class='booking-info'>";
                echo "<h2>Service: " . htmlspecialchars($row["service_title"]) . "</h2>";
                echo "<p><strong>Booked by:</strong> " . htmlspecialchars($row["user_name"]) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($row["user_email"]) . "</p>";
                echo "<p><strong>Phone:</strong> " . htmlspecialchars($row["user_phone"]) . "</p>";
                echo "<p><strong>Address:</strong> " . htmlspecialchars($row["address"]) . "</p>";
                echo "<p><strong>Booking Date:</strong> " . htmlspecialchars($row["booking_date"]) . "</p>";
                echo "</div>";
                echo "<form method='POST' action='send_otp.php'>";
                echo "<input type='hidden' name='booking_id' value='" . htmlspecialchars($row['booking_id']) . "'>";
                echo "<button type='submit' class='complete-button'><i class='fas fa-check-circle'></i> Complete</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-bookings'>No accepted bookings found.</p>";
        }
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
