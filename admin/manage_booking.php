<?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to manage bookings.'); window.location.href = '../login.php';</script>";
    exit();
}

$provider_email = $_SESSION['email']; // Get the provider's email from the session

// Fetch the bookings for the services provided by this provider
$sql = "SELECT b.booking_id, b.service_id, b.user_name, b.user_email, b.user_phone, 
               b.address, b.payment_method, b.special_requests, b.booking_date, b.status, 
               s.service_title, s.service_image 
        FROM bookings b
        JOIN services s ON b.service_id = s.service_id 
        WHERE s.provider_email = ?  -- Filter by the provider's email
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
    <link rel="stylesheet" href="css/styles.css"> <!-- Include your paid CSS -->
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
                            <?php if ($row['status'] == 'pending'): ?>
                                <form method="POST" action="accept_booking.php" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                    <button type="submit" class="accept-btn">Accept</button>
                                </form>
                                <form method="POST" action="reject_booking.php" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                    <button type="submit" class="reject-btn">Reject</button>
                                </form>
                            <?php else: ?>
                                <p><?php echo ucfirst($row['status']); ?></p>
                            <?php endif; ?>
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
