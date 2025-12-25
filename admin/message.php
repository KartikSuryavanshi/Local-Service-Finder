<?php
session_start();
include 'connection.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to send messages.'); window.location.href = '../login.php';</script>";
    exit();
}

$user_email = $_SESSION['email']; // Get the user's email from the session

// Check if service_id and provider_email are provided in the URL
if (isset($_GET['service_id']) && isset($_GET['provider_email'])) {
    $service_id = $_GET['service_id'];
    $provider_email = $_GET['provider_email'];

    // Fetch messages for this service between the user and provider
    $sql = "SELECT * FROM messages WHERE service_id = ? AND (sender_email = ? OR recipient_email = ?) ORDER BY timestamp ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $service_id, $user_email, $user_email);
    $stmt->execute();
    $messages = $stmt->get_result();
} else {
    echo "<script>alert('Please select specified order'); window.location.href = 'user_bookings.php';</script>";
    exit();
}

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $message_text = $_POST['message_text'];

    // Insert new message into database
    $sql = "INSERT INTO messages (service_id, sender_email, recipient_email, message_text) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $service_id, $user_email, $provider_email, $message_text);

    if ($stmt->execute()) {
        echo "<script>alert('Message sent!'); window.location.href='messages.php?service_id=$service_id&provider_email=$provider_email';</script>";
    } else {
        echo "<script>alert('Error sending message.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f1e7f6; /* Light purple background */ /* Background color similar to chat apps */
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background: #ffffff; /* White background for chat area */
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333; /* Dark color for headings */
        }

        .message-list {
            background-color: #f1f1f1; /* Light background for message area */
            padding: 15px;
            border-radius: 8px;
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
        }

        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            position: relative;
            max-width: 80%; /* Limit message width */
        }

        .message.sender {
            background-color: #dcf8c6; /* Light green background for sent messages */
            align-self: flex-end; /* Align sent messages to the right */
        }

        .message.recipient {
            background-color: #ffffff; /* White background for received messages */
            align-self: flex-start; /* Align received messages to the left */
        }

        .message strong {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555; /* Darker color for sender's name */
        }

        .message p {
            margin: 0;
            color: #333; /* Text color */
        }

        .message small {
            position: absolute;
            bottom: 5px;
            right: 10px;
            font-size: 0.7em;
            color: #aaa; /* Light color for timestamp */
        }

        .message-form {
            background-color: #ffffff; /* White background for form */
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .message-form textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none; /* Prevent resizing */
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            font-family: inherit; /* Use the same font as body */
        }

        .send-button {
            background-color: #007bff; /* Primary blue color for button */
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1em;
        }

        .send-button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        /* Media query for mobile responsiveness */
        @media (max-width: 600px) {
            .container {
                margin: 10px; /* Less margin on smaller screens */
                padding: 15px;
            }

            .send-button {
                width: 100%; /* Full width for button */
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Conversation with Provider</h2>

    <div class="message-list">
        <?php if ($messages->num_rows > 0): ?>
            <?php while ($message = $messages->fetch_assoc()): ?>
                <div class="message <?php echo $message['sender_email'] === $user_email ? 'sender' : 'recipient'; ?>">
                    <strong><?php echo htmlspecialchars($message['sender_email']); ?>:</strong>
                    <p><?php echo htmlspecialchars($message['message_text']); ?></p>
                    <small><?php echo $message['timestamp']; ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No messages yet.</p>
        <?php endif; ?>
    </div>

    <div class="message-form">
        <form method="POST" action="">
            <textarea name="message_text" placeholder="Type your message here..." required></textarea>
            <button type="submit" name="send_message" class="send-button">Send Message</button>
        </form>
    </div>
</div>

</body>
</html>

<?php
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
