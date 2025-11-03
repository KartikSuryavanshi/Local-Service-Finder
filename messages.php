<?php
session_start();
include 'connection.php'; // Include your database connection

// Check if the provider is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to view your messages.'); window.location.href = '../login.php';</script>";
    exit();
}

$provider_email = $_SESSION['email']; // Get the provider's email from the session

// Fetch all messages sent to this service provider
$sql = "SELECT m.service_id, m.message_text, m.sender_email, m.recipient_email, m.timestamp, s.service_title
        FROM messages m
        JOIN bookings b ON m.service_id = b.service_id
        JOIN services s ON m.service_id = s.service_id
        WHERE m.recipient_email = ?
        ORDER BY m.timestamp DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $provider_email);
$stmt->execute();
$messages = $stmt->get_result();

// Handle the reply functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $message_text = $_POST['message_text'];
    $service_id = $_POST['service_id'];
    $recipient_email = $_POST['recipient_email']; // The sender's email (user's email)

    // Insert the reply message into the database
    $reply_sql = "INSERT INTO messages (service_id, message_text, sender_email, recipient_email, timestamp) 
                  VALUES (?, ?, ?, ?, NOW())";
    $reply_stmt = $conn->prepare($reply_sql);
    $reply_stmt->bind_param("isss", $service_id, $message_text, $provider_email, $recipient_email);
    $reply_stmt->execute();
    $reply_stmt->close();
    
    // Redirect to the same page to refresh the messages (optional)
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages from Users</title>
    <style>
        /* CSS styles for the message section */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1e7f6; /* Light purple background */
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        .message-list {
            max-height: 500px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .message-card {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .message-card h3 {
            font-size: 1.2em;
            margin: 0 0 10px;
            color: #007bff;
        }

        .message-card p {
            margin: 5px 0;
        }

        .message-card small {
            display: block;
            color: #666;
            margin-top: 10px;
        }

        .reply-form {
            margin-top: 20px;
        }

        .reply-form textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .send-button {
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .send-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Messages from Users</h1>

    <div class="message-list">
        <?php if ($messages->num_rows > 0): ?>
            <?php while ($message = $messages->fetch_assoc()): ?>
                <div class="message-card">
                    <h3><?php echo htmlspecialchars($message['service_title']); ?></h3>
                    <p><strong>From:</strong> <?php echo htmlspecialchars($message['sender_email']); ?></p>
                    <p><?php echo htmlspecialchars($message['message_text']); ?></p>
                    <small><?php echo $message['timestamp']; ?></small>

                    <!-- Reply Form -->
                    <form method="POST" class="reply-form">
                        <textarea name="message_text" placeholder="Type your reply..." required></textarea>
                        <input type="hidden" name="service_id" value="<?php echo $message['service_id']; ?>">
                        <input type="hidden" name="recipient_email" value="<?php echo $message['sender_email']; ?>">
                        <button type="submit" name="send_message" class="send-button">Send Reply</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No messages found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
