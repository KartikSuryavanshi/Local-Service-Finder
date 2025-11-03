<?php
session_start();
include 'connection.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to view your bookings.'); window.location.href = '../login.php';</script>";
    exit();
}

$user_email = $_SESSION['email']; // Get the user's email from the session

// Fetch booked orders for the logged-in user
$sql = "SELECT b.booking_id, b.booking_date, b.service_id, b.user_name, b.user_email, b.address, b.payment_method, b.special_requests, b.status, b.otp, s.service_title, s.provider_email
        FROM bookings b
        JOIN services s ON b.service_id = s.service_id
        WHERE b.user_email = ?
        ORDER BY b.booking_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Enhanced CSS styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #6a11cb, #2575fc); /* Gradient background */
            background-size: cover;
            color: #fff;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent background for container */
            border-radius: 12px; /* Rounded corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        h1 {
            text-align: center;
            color: #f39c12; /* Heading color */
            margin-bottom: 20px;
            font-size: 2em; /* Increased font size for heading */
        }

        .booking-card {
            background-color: rgba(255, 255, 255, 0.9); /* White background for booking card */
            color: #333;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            padding: 20px;
            transition: transform 0.2s ease; /* Added transition for card hover */
        }

        .booking-card:hover {
            transform: scale(1.02); /* Slightly increase card size on hover */
        }

        .booking-card h2 {
            font-size: 1.5em;
            margin: 0 0 10px;
            color: #e74c3c; /* Service title color */
        }

        .booking-card p {
            margin: 5px 0;
        }

        .talk-button {
            background-color: #007bff;
            color: white;
            padding: 4px 18px; /* Increased padding for better button size */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1em; /* Increased font size for better readability */
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease; /* Added transition for background and transform */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Added shadow to button */
        }

        .talk-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: scale(1.05); /* Slightly increase size on hover */
        }

        .talk-button:active {
            transform: scale(0.95); /* Slightly shrink on click */
        }

        .otp {
            font-weight: bold;
            color: #e74c3c;
        }
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
    font-size: 2.5em; /* Larger heading font */
}

.booking-card {
    background-color: rgba(255, 255, 255, 0.85); /* Light background for booking cards */
    color: #333;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    margin-bottom: 25px;
    padding: 25px;
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Added smooth transitions */
}

.booking-card:hover {
    transform: scale(1.05); /* Slightly scale card on hover */
    box-shadow: 0 6px 30px rgba(0, 0, 0, 0.4); /* Enhanced shadow on hover */
}

.booking-card h2 {
    font-size: 1.6em;
    margin: 0 0 12px;
    color: #5dacbd; /* Teal color for service titles */
}

.booking-card p {
    margin: 8px 0;
}

.talk-button {
    background-color: #118a7e; /* Teal color for button */
    color: white;
    padding: 8px 20px; /* Increased padding */
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.2em;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.3s ease;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); /* Subtle shadow */
}

.talk-button:hover {
    background-color: #93e4c1; /* Light teal on hover */
    transform: scale(1.05); /* Slightly increase size on hover */
}

.talk-button:active {
    transform: scale(0.95); /* Shrink on click */
}

.otp {
    font-weight: bold;
    color: #e74c3c; /* Red color for OTP */
}

.booking-card p strong {
    color: #118a7e; /* Teal color for strong elements */
}

    </style>
</head>
<body>

<div class="container">
    <h1>Your Bookings</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="booking-card">
                <h2><?php echo htmlspecialchars($row['service_title']); ?></h2>
                <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($row['booking_date']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($row['user_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($row['user_email']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($row['payment_method']); ?></p>
                <p><strong>Booking Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>

                <?php
                    // Display OTP only if the status is 'accepted'
                    if ($row['status'] == 'accepted') {
                        echo "<p class='otp'><strong>OTP:</strong> " . htmlspecialchars($row['otp']) . "</p>";
                    }
                ?>

                <p><strong>Special Requests:</strong> <?php echo htmlspecialchars($row['special_requests']); ?></p>
                
                <!-- Talk to Service Provider Button -->
                <a href="message.php?service_id=<?php echo $row['service_id']; ?>&provider_email=<?php echo $row['provider_email']; ?>" class="talk-button">Talk to Service Provider</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
