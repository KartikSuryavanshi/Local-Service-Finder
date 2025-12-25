<?php
session_start(); // Start the session
include 'connection.php'; // Include your database connection

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to book a service.'); window.location.href='login.php';</script>";
    exit();
}

$user_email = $_SESSION['email']; // Get the logged-in user's email

// Query to select all services from the database
$sql = "SELECT * FROM services ORDER BY service_id DESC";
$result = $conn->query($sql);

// Initialize error message variable
$error_message = "";

// Check if the booking form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_service'])) {
    // Get booking details from the POST request
    $service_id = $_POST['service_id'];
    $user_name = $_POST['user_name'];
    $entered_email = $_POST['user_email']; // Get the email entered in the form
    $user_phone = $_POST['user_phone'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];
    $special_requests = $_POST['special_requests'];
    $booking_date = date('Y-m-d H:i:s'); // Set current date and time

    // Check if the entered email matches the logged-in user's email
    if ($entered_email !== $user_email) {
        $error_message = "The email address must match your logged-in email."; // Set the error message
    } else {
        // Prepare SQL statement to insert booking
        $stmt = $conn->prepare("INSERT INTO bookings (service_id, user_name, user_email, user_phone, address, payment_method, special_requests, booking_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $service_id, $user_name, $entered_email, $user_phone, $address, $payment_method, $special_requests, $booking_date);

        if ($stmt->execute()) {
            echo "<script>alert('Booking successful!'); window.location.href='sidebar.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Services</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #4a90e2, #9013fe); /* Gradient background */
            color: #fff; /* Default text color */
            animation: backgroundChange 15s infinite; /* Animation for background color change */
        }

        @keyframes backgroundChange {
            0% { background: #4a90e2; }
            50% { background: #9013fe; }
            100% { background: #4a90e2; }
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7); /* Darker semi-transparent background */
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            animation: fadeIn 1s; /* Fade-in animation */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        h1 {
            text-align: center;
            color: #f39c12; /* Bright color for heading */
            margin-bottom: 20px;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.7); /* Text shadow for depth */
            animation: bounce 2s infinite; /* Bounce animation for header */
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        .service-card {
            background-color: rgba(255, 255, 255, 0.9); /* White background for cards */
            color: #333; /* Text color for cards */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            transition: transform 0.2s, box-shadow 0.2s; /* Transition for transform and box-shadow */
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .service-card h2 {
            font-size: 1.8em;
            margin: 0 0 10px;
            color: #e74c3c; /* Accent color for headings */
        }

        .service-card p {
            margin: 10px 0;
            color: #555; /* Dark text for contrast */
        }

        .service-card .provider {
            font-weight: bold;
            color: #6c757d; /* Lighter color for provider text */
        }

        .book-now-button {
            display: inline-block;
            background-color: #28a745; /* Green background for button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none; /* Remove underline for links */
            transition: background-color 0.3s ease, transform 0.2s; /* Added transform for scale */
            text-align: center;
        }

        .book-now-button:hover {
            background-color: #218838; /* Darker green on hover */
            transform: scale(1.05); /* Scale effect on hover */
        }

        .booking-form {
            display: none; /* Hidden by default */
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* White background for form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.5s; /* Slide-in animation */
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .booking-form input,
        .booking-form textarea,
        .booking-form select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s; /* Transition for border color */
        }

        .booking-form input:focus,
        .booking-form textarea:focus,
        .booking-form select:focus {
            border-color: #007bff; /* Highlight border on focus */
            outline: none; /* Remove default outline */
        }

        .submit-button {
            background-color: #007bff; /* Blue background for button */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.2s; /* Added transform for scale */
        }

        .submit-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: scale(1.05); /* Scale effect on hover */
        }

        .error-message {
            color: #e74c3c; /* Red color for error messages */
            font-size: 0.9em; /* Smaller font size for error messages */
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #63bce5, #7ed5ea); /* Light blue gradient background */
            color: #1f3b5b; /* Dark text color for contrast */
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Light background */
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #1f3b5b; /* Darker heading color for contrast */
            margin-bottom: 20px;
        }

        .service-card {
            background-color: rgba(103, 181, 232, 0.9); /* Light blue card background */
            color: #1f3b5b; /* Darker text for readability */
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .service-card h2 {
            font-size: 1.8em;
            margin: 0 0 10px;
            color: #3778c2; /* Lighter blue for headings */
        }

        .service-card p {
            margin: 10px 0;
            color: #1f3b5b; /* Darker text for readability */
        }

        .service-card .provider {
            font-weight: bold;
            color: #63bce5; /* Accent color for provider text */
        }

        .book-now-button {
            display: inline-block;
            background-color: #3778c2; /* Light blue button background */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s;
            text-align: center;
        }

        .book-now-button:hover {
            background-color: #28559a; /* Darker blue on hover */
            transform: scale(1.05);
        }

        .booking-form {
            display: none;
            margin-top: 20px;
            background-color: rgba(103, 181, 232, 0.9); /* Light blue form background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .booking-form input,
        .booking-form textarea,
        .booking-form select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #63bce5; /* Light blue border */
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .booking-form input:focus,
        .booking-form textarea:focus,
        .booking-form select:focus {
            border-color: #28559a;
            outline: none;
        }

        .submit-button {
            background-color: #63bce5; /* Light blue button background */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.2s;
        }

        .submit-button:hover {
            background-color: #28559a; /* Darker blue on hover */
            transform: scale(1.05);
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.9em;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Available Services</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="service-card">
                <h2><?php echo htmlspecialchars($row['service_title']); ?></h2>
                <p><?php echo htmlspecialchars($row['service_description']); ?></p>
                <p class="provider">Provided by: <?php echo htmlspecialchars($row['provider_email']); ?></p>
                <p class="provider">Contact Email: <?php echo htmlspecialchars($row['contact_email']); ?></p>
                <p class="provider">Contact Phone: <?php echo htmlspecialchars($row['contact_phone']); ?></p>
                <button class="book-now-button" onclick="showBookingForm(<?php echo $row['service_id']; ?>)">Book Now</button>

                <!-- Booking Form -->
                <div class="booking-form" id="booking-form-<?php echo $row['service_id']; ?>">
                    <h3>Booking Form</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="service_id" value="<?php echo $row['service_id']; ?>">
                        <input type="text" name="user_name" placeholder="Your Name" required>
                        <input type="email" name="user_email" placeholder="Your Email" required>
                        <?php if ($error_message): ?>
                            <div class="error-message"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <input type="tel" name="user_phone" placeholder="Your Phone" required>
                        <input type="text" id="address-<?php echo $row['service_id']; ?>" name="address" placeholder="Your Address" required>
                        <button type="button" class="submit-button" onclick="getLocation(<?php echo $row['service_id']; ?>)">Get Location</button>
                        <select name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="paypal">Cash On Delivery</option>
                            <option value="bank_transfer">Upi</option>
                        </select>
                        <textarea name="special_requests" placeholder="Special Requests (optional)"></textarea>
                        <button type="submit" name="book_service" class="submit-button">Confirm Booking</button>
                    </form>
                </div>
            </div>

            <script>
                // Function to show the booking form
                function showBookingForm(serviceId) {
                    var form = document.getElementById('booking-form-' + serviceId);
                    form.style.display = (form.style.display === 'block') ? 'none' : 'block';
                }

                // Function to get the user's location
                function getLocation(serviceId) {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var lat = position.coords.latitude;
                            var lon = position.coords.longitude;
                            document.getElementById('address-' + serviceId).value = "Lat: " + lat + ", Lon: " + lon; // Set the coordinates in the address field
                        }, function() {
                            alert("Unable to retrieve your location. Please enable location services.");
                        });
                    } else {
                        alert("Geolocation is not supported by this browser.");
                    }
                }
            </script>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No services available.</p>
    <?php endif; ?>
</div>

</body>
</html>
