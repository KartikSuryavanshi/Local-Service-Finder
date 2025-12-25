<?php
include 'connection.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the posted data
    $service_id = intval($_POST['service_id']);
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $special_requests = htmlspecialchars(trim($_POST['special_requests']));
    
    // Ensure the form has the booking_id and action
    if (isset($_POST['booking_id']) && isset($_POST['action'])) {
        $booking_id = intval($_POST['booking_id']);
        $action = $_POST['action'];

        // Prepare SQL based on action (approve/reject)
        if ($action == 'approve') {
            $sql = "UPDATE bookings SET status = 'Approved' WHERE id = ?";
        } elseif ($action == 'reject') {
            $sql = "UPDATE bookings SET status = 'Rejected' WHERE id = ?";
        } else {
            echo "Invalid action.";
            exit();
        }

        // Execute the prepared SQL statement
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $booking_id);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Error preparing the statement.";
        }

        // Redirect after processing the booking
        header("Location: manage_bookings.php");
        exit();
    } else {
        echo "Booking ID or action is missing.";
    }
} else {
    echo "Invalid request method.";
}

// Validate inputs (basic validation)
if (empty($name) || empty($email) || empty($phone)) {
    echo "Please fill in all required fields.";
    exit;
}

// Fetch service details for confirmation
$sql = "SELECT * FROM services WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $service_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $service = $result->fetch_assoc();
} else {
    echo "Service not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking - <?php echo htmlspecialchars($service['service_title']); ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('your-background-image.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 2.5em;
            text-transform: uppercase;
        }

        .service-details {
            border: 1px solid #2c3e50;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #ecf0f1;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .service-details img {
            width: 100%;
            max-width: 300px;
            border-radius: 10px;
            margin-bottom: 15px;
            border: 3px solid #2c3e50;
        }

        .payment-option {
            display: flex;
            align-items: center;
            margin: 15px 0;
            padding: 15px;
            border: 2px solid #2c3e50;
            border-radius: 10px;
            background-color: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .payment-option:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .payment-option h3 {
            margin: 0;
            color: #2c3e50;
            flex-grow: 1;
        }

        input[type="radio"] {
            margin-right: 15px;
            cursor: pointer;
            accent-color: #2c3e50; /* Modern browsers */
        }

        .submit-button {
            display: block;
            background-color: #2ecc71;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin: 0 auto;
            width: 50%;
        }

        .submit-button:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }

        .address-section {
            margin-top: 20px;
        }

        .address-input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .location-message {
            margin: 15px 0;
            font-size: 1em;
            color: #e74c3c; /* Red color for error message */
        }

        @media (max-width: 600px) {
            .container {
                width: 95%;
                padding: 10px;
            }

            .submit-button {
                width: 80%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Confirm Booking</h1>

    <div class="service-details">
        <h2><?php echo htmlspecialchars($service['service_title']); ?></h2>
        <img src="../uploads/<?php echo htmlspecialchars($service['service_image']); ?>" alt="Service Image">
        <p><?php echo htmlspecialchars($service['service_description']); ?></p>
        <p><b>Provider Email:</b> <?php echo htmlspecialchars($service['contact_email']); ?></p>
        <p><b>Provider Phone:</b> <?php echo htmlspecialchars($service['contact_phone']); ?></p>
    </div>

    <h2>Select Payment Method</h2>

    <form action="booking_success.php" method="POST" id="booking-form">
        <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
        <input type="hidden" name="name" value="<?php echo $name; ?>">
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <input type="hidden" name="phone" value="<?php echo $phone; ?>">
        <input type="hidden" name="special_requests" value="<?php echo $special_requests; ?>">

        <label class="payment-option">
            <input type="radio" name="payment_method" value="card" required> 
            <h3>Card Payment</h3>
            <p>Pay securely with your credit or debit card.</p>
        </label>

        <label class="payment-option">
            <input type="radio" name="payment_method" value="cod" required> 
            <h3>Cash on Delivery (COD)</h3>
            <p>Pay cash to the provider upon service completion.</p>
        </label>

        <label class="payment-option">
            <input type="radio" name="payment_method" value="upi" required> 
            <h3>UPI Payment</h3>
            <p>Pay using UPI. (E.g., Google Pay, PhonePe, etc.)</p>
        </label>

        <div class="address-section">
            <h2>Provide Your Address</h2>
            <button type="button" onclick="getLocation()">Get My Location</button>
            <div id="location-message" class="location-message"></div>
            <input type="text" name="address" id="address" class="address-input" placeholder="Type your address here..." required>
        </div>

        <button type="submit" class="submit-button">Confirm Booking</button>
    </form>
</div>

<script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        document.getElementById('location-message').innerText = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    var latlon = position.coords.latitude + "," + position.coords.longitude;
    document.getElementById('address').value = "Latitude: " + position.coords.latitude + " Longitude: " + position.coords.longitude;
    document.getElementById('location-message').innerText = "Location fetched successfully!";
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            document.getElementById('location-message').innerText = "User denied the request for Geolocation.";
            break;
        case error.POSITION_UNAVAILABLE:
            document.getElementById('location-message').innerText = "Location information is unavailable.";
            break;
        case error.TIMEOUT:
            document.getElementById('location-message').innerText = "The request to get user location timed out.";
            break;
        case error.UNKNOWN_ERROR:
            document.getElementById('location-message').innerText = "An unknown error occurred.";
            break;
    }
}
</script>

</body>
</html>
