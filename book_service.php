<?php
include 'connection.php'; // Include your database connection

// Check if the service ID is provided in the URL
if (isset($_GET['id'])) {
    $service_id = intval($_GET['id']);

    // Fetch service details from the database
    $sql = "SELECT * FROM services WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if service exists
    if ($result->num_rows > 0) {
        $service = $result->fetch_assoc();
    } else {
        echo "Service not found.";
        exit;
    }
} else {
    echo "No service ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service - <?php echo htmlspecialchars($service['service_title']); ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('your-background-image.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            color: #fff; /* Default text color */
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            margin-top: 40px; /* Space from top */
        }

        h1 {
            text-align: center;
            color: #f39c12; /* Bright color for heading */
            margin-bottom: 20px;
        }

        .service-details {
            background-color: rgba(255, 255, 255, 0.9); /* White background for service details */
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            color: #333; /* Text color for service details */
        }

        .service-details img {
            width: 100%; /* Full width */
            max-width: 300px; /* Max width for the image */
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #fff;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .submit-button {
            display: block;
            background-color: #28a745; /* Green background for button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease;
            margin: 0 auto; /* Center align */
        }

        .submit-button:hover {
            background-color: #218838; /* Darker green on hover */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Book Service</h1>

    <div class="service-details">
        <h2><?php echo htmlspecialchars($service['service_title']); ?></h2>
        <img src="../uploads/<?php echo htmlspecialchars($service['service_image']); ?>" alt="Service Image">
        <p><?php echo htmlspecialchars($service['service_description']); ?></p>
        <p><b>Provider Email:</b> <?php echo htmlspecialchars($service['contact_email']); ?></p>
        <p><b>Provider Phone:</b> <?php echo htmlspecialchars($service['contact_phone']); ?></p>
    </div>

    <form action="process_booking.php" method="POST">
        <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
        
        <div class="form-group">
            <label for="name">Your Name:</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="form-group">
            <label for="email">Your Email:</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="phone">Your Phone Number:</label>
            <input type="text" name="phone" id="phone" required>
        </div>

        <div class="form-group">
            <label for="special_requests">Special Requests:</label>
            <textarea name="special_requests" id="special_requests" rows="4"></textarea>
        </div>

        <button type="submit" class="submit-button">Submit Booking</button>
    </form>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
