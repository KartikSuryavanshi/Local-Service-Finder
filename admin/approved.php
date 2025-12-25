<?php
include 'connection.php'; // Include your database connection file
session_start(); // Start session to manage access

// Fetch all providers with 'pending' status
$sql = "SELECT id, provider_name, email, profile_picture, aadhar_card FROM service_providers WHERE approved = 'pending'";
$result = $conn->query($sql);

// Handle approval or rejection
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'], $_POST['provider_id'])) {
    $action = $_POST['action'];
    $provider_id = $_POST['provider_id'];
    
    if ($action == "approve") {
        $updateSql = "UPDATE service_providers SET approved = 'approved' WHERE id = ?";
    } elseif ($action == "reject") {
        $updateSql = "UPDATE service_providers SET approved = 'rejected' WHERE id = ?";
    }
    
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("i", $provider_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: approved.php"); // Refresh page after action
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provider Approval</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
         body {
            
            font-family: Arial, sans-serif;
            background-color: #9b4dca;
                        display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .approval-container {
            
            width: 80%;
            max-width: 900px;
            margin: auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #5c2a9d; /* Light purple */
        }
        .provider {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .provider:last-child {
            border-bottom: none;
        }
        .profile-pic, .aadhar-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 20px;
            cursor: pointer; /* Add pointer for clickable */
        }
        .profile-pic img, .aadhar-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            color: #fff;
            transition: background-color 0.3s ease;
        }
        .btn.approve {
            background-color: #9b4dca; /* Light purple background for approve */
        }
        .btn.approve:hover {
            background-color: #7a39a3; /* Darker purple on hover */
        }
        .btn.reject {
            background-color: #dc3545;
        }
        .btn.reject:hover {
            background-color: #b02a37; /* Darker red on hover */
        }
        .header {
            color: #9b4dca; /* Light purple header color */
        }
    </style>
    <script>
        function openFullScreen(imageSrc) {
            const fullScreenImage = document.createElement("img");
            fullScreenImage.src = imageSrc;
            fullScreenImage.style.position = "fixed";
            fullScreenImage.style.top = "50%";
            fullScreenImage.style.left = "50%";
            fullScreenImage.style.transform = "translate(-50%, -50%)";
            fullScreenImage.style.maxWidth = "90%";
            fullScreenImage.style.maxHeight = "90%";
            fullScreenImage.style.zIndex = "9999";
            fullScreenImage.onclick = function() {
                document.body.removeChild(fullScreenImage);
            };
            document.body.appendChild(fullScreenImage);
        }
    </script>
</head>
<body>

<div class="approval-container">
    <div class="header">Approve or Reject Service Providers</div>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="provider">
                <div class="profile-pic" onclick="openFullScreen('<?php echo htmlspecialchars($row['profile_picture']); ?>')">
                    <img src="<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="Profile Picture">
                </div>
                <div>
                    <h3><?php echo htmlspecialchars($row['provider_name']); ?></h3>
                    <p><?php echo htmlspecialchars($row['email']); ?></p>
                </div>
                <div class="aadhar-pic" onclick="openFullScreen('<?php echo htmlspecialchars($row['aadhar_card']); ?>')">
                    <img src="<?php echo htmlspecialchars($row['aadhar_card']); ?>" alt="Aadhar Card">
                </div>
                <div class="actions">
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="provider_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="action" value="approve" class="btn approve">Approve</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="provider_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="action" value="reject" class="btn reject">Reject</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center;">No providers are pending approval at the moment.</p>
    <?php endif; ?>
</div>

</body>
</html>
