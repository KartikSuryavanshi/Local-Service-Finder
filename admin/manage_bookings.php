<?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to manage bookings.'); window.location.href = '../login.php';</script>";
    exit();
}

$provider_email = $_SESSION['email']; // Get the provider's email from the session

// Fetch only the pending bookings for the services provided by this provider
$sql = "SELECT b.booking_id, b.service_id, b.user_name, b.user_email, b.user_phone, 
               b.address, b.payment_method, b.special_requests, b.booking_date, b.status, 
               s.service_title, s.service_image 
        FROM bookings b
        JOIN services s ON b.service_id = s.service_id 
        WHERE s.provider_email = ? AND b.status = 'pending'  -- Only pending bookings
        ORDER BY b.booking_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $provider_email); // Bind the provider's email to the SQL query
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .accept-btn, .reject-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
        }

        .accept-btn {
            background-color: #2ecc71;
        }

        .reject-btn {
            background-color: #e74c3c;
        }
         body {
            font-family: 'Arial', sans-serif;
            background-color: #f3e6f7; /* Light purple background */
            color: #333;
            margin: 0;
            padding: 20px;
        }

        /* Main header styling */
        h1 {
            text-align: center;
            font-size: 2.5em;
            color: #4a90e2; /* Light blue */
            margin-bottom: 20px;
        }

        /* Booking entry styling */
        .booking-entry {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Service title */
        .service-title {
            font-size: 1.8em;
            color: #4a90e2;
            margin-bottom: 10px;
        }

        /* Text for each booking */
        .booking-details p {
            font-size: 1.1em;
            color: #555;
            margin: 5px 0;
        }

        /* Styling for no bookings message */
        .no-bookings {
            text-align: center;
            font-size: 1.5em;
            color: #888;
        }

        /* Add some spacing between the content */
        br {
            margin-bottom: 15px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            h1 {
                font-size: 2em;
            }
            .booking-entry {
                padding: 15px;
            }
            .service-title {
                font-size: 1.6em;
            }
            .booking-details p {
                font-size: 1em;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Manage Bookings</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th>User</th>
                    <th>Payment Method</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="../uploads/<?php echo htmlspecialchars($row['service_image']); ?>" alt="Service Image" style="max-width: 100px;">
                            <p><?php echo htmlspecialchars($row['service_title']); ?></p>
                        </td>
                        <td>
                            <p><?php echo htmlspecialchars($row['user_name']); ?></p>
                            <p><?php echo htmlspecialchars($row['user_email']); ?></p>
                            <p><?php echo htmlspecialchars($row['user_phone']); ?></p>
                        </td>
                        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($row['booking_date']))); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td class="action-buttons">
                            <form method="POST" action="accept_booking.php" style="display: inline;">
                                <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                <button type="submit" class="accept-btn">Accept</button>
                            </form>
                            <form method="POST" action="reject_booking.php" style="display: inline;">
                                <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                <button type="submit" class="reject-btn">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No new bookings available.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
