<?php
session_start();
if (!isset($_SESSION['provider_id'])) {
    header("Location: login.php");
    exit();
}

include 'connection.php';

$provider_id = $_SESSION['provider_id'];

$sql = "SELECT b.id, u.username, s.service_title, b.booking_date 
        FROM bookings b 
        JOIN users u ON b.user_id = u.id 
        JOIN services s ON b.service_id = s.id 
        WHERE s.provider_id = '$provider_id'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
</head>
<style>
    /* Body and overall page styling */
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
div {
    background-color: #ffffff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Service title */
h2 {
    font-size: 1.8em;
    color: #4a90e2;
    margin-bottom: 10px;
}

/* Text for each booking */
p {
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
<body>
    <h1>Manage Bookings</h1>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2>Service: " . $row["service_title"] . "</h2>";
            echo "<p>Booked by: " . $row["username"] . "</p>";
            echo "<p>Booking Date: " . $row["booking_date"] . "</p>";
            echo "</div><br>";
        }
    } else {
        echo "No bookings found.";
    }
    $conn->close();
    ?>
</body>
</html>
