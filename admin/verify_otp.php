<?php
include 'connection.php';

if (isset($_POST['verify_otp'])) {
    $booking_id = $_POST['booking_id'];
    $entered_otp = $_POST['otp'];

    // Retrieve OTP from the database
    $sql = "SELECT otp FROM bookings WHERE booking_id = '$booking_id'";  // Changed `id` to `booking_id`
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $stored_otp = $result->fetch_assoc()['otp'];

        if ($entered_otp == $stored_otp) {
            // If OTP is correct, mark the booking as completed and delete from bookings
            $sql = "UPDATE bookings SET status = 'completed' WHERE booking_id = '$booking_id'";  // Change status instead of deleting
            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Booking completed successfully!'); window.location.href = 'location_settings.php';</script>";
            } else {
                echo "<script>alert('Error completing booking. Please try again later.'); window.location.href = 'location_settings.php';</script>";
            }
        } else {
            echo "<div class='alert alert-danger'>Incorrect OTP. Please try again.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>No booking found for the provided ID.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS */
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .card {
            padding: 20px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        .form-control {
            margin-bottom: 20px;
        }
        .btn-primary {
            width: 100%;
        }
        .alert {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Verify OTP</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($_GET['booking_id']);?>">
                    <div class="form-group">
                        <label for="otp">Enter OTP:</label>
                        <input type="text" class="form-control" name="otp" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="verify_otp">Verify</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
