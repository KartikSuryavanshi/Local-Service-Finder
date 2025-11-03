<?php
session_start();
include 'connection.php'; // Include your database connection

// Check if the service provider is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to view the dashboard.'); window.location.href = '../login.php';</script>";
    exit();
}

$email = $_SESSION['email']; // Get the provider's email from the session

// Query to get the number of accepted orders
$sql_accepted_orders = "SELECT COUNT(*) as accepted_orders FROM bookings WHERE user_email = ? AND status = 'accepted'";
$stmt_accepted_orders = $conn->prepare($sql_accepted_orders);
$stmt_accepted_orders->bind_param("s", $email);
$stmt_accepted_orders->execute();
$result_accepted_orders = $stmt_accepted_orders->get_result();
$accepted_orders_count = $result_accepted_orders->fetch_assoc()['accepted_orders'];

// Query to get the number of favorites (users who added the provider)
$sql_favorites = "SELECT COUNT(*) as favorites FROM heart WHERE provider_name = ?";
$stmt_favorites = $conn->prepare($sql_favorites);
$stmt_favorites->bind_param("s", $email);
$stmt_favorites->execute();
$result_favorites = $stmt_favorites->get_result();
$favorites_count = $result_favorites->fetch_assoc()['favorites'];

// Query to get the number of pending requests for approval
$sql_pending_requests = "SELECT COUNT(*) as pending_requests FROM bookings WHERE user_email = ? AND status = 'pending'";
$stmt_pending_requests = $conn->prepare($sql_pending_requests);
$stmt_pending_requests->bind_param("s", $email);
$stmt_pending_requests->execute();
$result_pending_requests = $stmt_pending_requests->get_result();
$pending_requests_count = $result_pending_requests->fetch_assoc()['pending_requests'];

// Query to get the top 5 customers who booked the service the most
$sql_top_customers = "SELECT b.user_name, COUNT(b.booking_id) AS bookings_count 
                      FROM bookings b 
                      WHERE b.user_email = ? 
                      GROUP BY b.user_email 
                      ORDER BY bookings_count DESC 
                      LIMIT 5";
$stmt_top_customers = $conn->prepare($sql_top_customers);
$stmt_top_customers->bind_param("s", $email);
$stmt_top_customers->execute();
$result_top_customers = $stmt_top_customers->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Provider Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            width: 60%;  /* Reduced size */
            max-width: 800px;
            margin: 0 auto;
            padding: 15px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .stats-card {
            background-color: #ecf0f1;
            color: #2c3e50;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stats-card h2 {
            margin-bottom: 15px;
        }

        .stats-card .value {
            font-size: 2em;
            color: #e74c3c;
        }

        .top-customers {
            margin-top: 30px;
        }

        .customer-list {
            list-style-type: none;
            padding: 0;
        }

        .customer-item {
            background-color: #ecf0f1;
            margin: 10px 0;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .customer-item strong {
            color: #2980b9;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h1>Welcome to Your Dashboard</h1>

    <!-- Accepted Orders Section -->
    <div class="stats-card">
        <h2>Accepted Orders</h2>
        <p class="value"><?php echo $accepted_orders_count; ?></p>
    </div>

    <!-- Favorites Section -->
    <div class="stats-card">
        <h2>Favorites</h2>
        <p class="value"><?php echo $favorites_count; ?></p>
    </div>

    <!-- Pending Requests Section -->
    <div class="stats-card">
        <h2>Pending Requests</h2>
        <p class="value"><?php echo $pending_requests_count; ?></p>
    </div>

    <!-- Top 5 Customers Section -->
    <div class="top-customers">
        <h2>Top 5 Customers</h2>
        <ul class="customer-list">
            <?php while ($row = $result_top_customers->fetch_assoc()): ?>
                <li class="customer-item">
                    <strong><?php echo htmlspecialchars($row['user_name']); ?></strong> - 
                    <span><?php echo $row['bookings_count']; ?> Bookings</span>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

</div>

</body>
</html>

<?php
// Close the prepared statements
$stmt_accepted_orders->close();
$stmt_favorites->close();
$stmt_pending_requests->close();
$stmt_top_customers->close();

// Close the database connection
$conn->close();
?>
