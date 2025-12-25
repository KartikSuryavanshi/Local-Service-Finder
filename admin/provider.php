<?php
session_start();
include 'connection.php';

// Ensure user ID is set in session for tracking favorites
$user_id = $_SESSION['user_id'];

// Fetch all service providers or filtered providers based on search input
$search_name = isset($_GET['search_name']) ? $_GET['search_name'] : '';
$sql = "SELECT provider_name, email, profile_picture FROM service_providers";
if ($search_name) {
    $sql .= " WHERE provider_name LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%" . $search_name . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Providers</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Existing and additional styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom right, #4a90e2, #9013fe);
            color: #fff;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #f39c12;
        }
        .search-box {
            text-align: center;
            margin-bottom: 20px;
        }
        .provider-card {
            display: flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .provider-card img {
            border-radius: 50%;
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }
        .provider-info {
            display: flex;
            flex-direction: column;
        }
        .provider-info h2 {
            font-size: 1.5em;
            margin: 0;
            color: #333;
        }
        .provider-info p {
            color: #666;
            margin: 5px 0;
        }
        .heart {
            font-size: 24px;
            color: gray;
            cursor: pointer;
            margin-left: auto;
        }
        .heart.favorited {
            color: red;
        }
        /* General reset and body styling */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(to bottom right, #4a90e2, #9013fe);
    color: #fff;
    line-height: 1.6;
}

/* Container for the entire content */
.container {
    width: 90%;
    max-width: 1200px;
    margin: 40px auto;
    padding: 30px;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(10px);
}

/* Header styling */
h1 {
    text-align: center;
    color: #f39c12;
    font-size: 2.5rem;
    margin-bottom: 30px;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
}

/* Search box */
.search-box {
    text-align: center;
    margin-bottom: 30px;
}

.search-box input[type="text"] {
    padding: 10px;
    width: 60%;
    max-width: 400px;
    border: none;
    border-radius: 25px;
    outline: none;
    font-size: 1rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.search-box button {
    padding: 10px 20px;
    border: none;
    background-color: #f39c12;
    color: #fff;
    border-radius: 25px;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.3s;
}

.search-box button:hover {
    background-color: #d98c0f;
}

/* Provider card styling */
.provider-card {
    display: flex;
    align-items: center;
    background-color: rgba(255, 255, 255, 0.9);
    color: #333;
    border-radius: 12px;
    margin-bottom: 20px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
}

.provider-card:hover {
    transform: scale(1.03);
}

/* Profile image */
.provider-card img {
    border-radius: 50%;
    width: 80px;
    height: 80px;
    object-fit: cover;
    margin-right: 20px;
    border: 2px solid #f39c12;
}

/* Provider information */
.provider-info {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.provider-info h2 {
    font-size: 1.5em;
    margin: 0;
    color: #333;
}

.provider-info p {
    color: #555;
    margin: 5px 0;
}

/* Heart icon */
.heart {
    font-size: 1.5rem;
    color: gray;
    cursor: pointer;
    margin-left: auto;
    transition: color 0.3s;
}

.heart.favorited {
    color: red;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .provider-card {
        flex-direction: column;
        align-items: flex-start;
        padding: 15px;
    }

    .provider-card img {
        width: 60px;
        height: 60px;
        margin-bottom: 15px;
    }

    .search-box input[type="text"] {
        width: 80%;
    }

    h1 {
        font-size: 2rem;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Our Service Providers</h1>

        <!-- Search form to filter by provider name -->
        <div class="search-box">
            <form method="GET" action="">
                <input type="text" name="search_name" placeholder="Search by provider name" value="<?php echo htmlspecialchars($search_name); ?>">
                <button type="submit">Filter</button>
            </form>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="provider-card">
                    <img src="<?php echo htmlspecialchars($row['profile_picture']); ?>" alt="Profile Photo">
                    <div class="provider-info">
                        <h2><?php echo htmlspecialchars($row['provider_name']); ?></h2>
                        <p>Email: <?php echo htmlspecialchars($row['email']); ?></p>
                    </div>
                    <span class="heart" onclick="toggleFavorite(this, '<?php echo htmlspecialchars($row['provider_name']); ?>', '<?php echo htmlspecialchars($row['email']); ?>', '<?php echo htmlspecialchars($row['profile_picture']); ?>')">&#x2764;</span>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No providers found.</p>
        <?php endif; ?>
    </div>

    <script>
        function toggleFavorite(element, name, email, profilePicture) {
            element.classList.toggle("favorited");
            const isFavorited = element.classList.contains("favorited");
            
            // AJAX request to save to the heart table
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "save_heart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send(`user_id=<?php echo $user_id; ?>&provider_name=${name}&email=${email}&profile_picture=${profilePicture}&favorited=${isFavorited}`);
        }
    </script>
</body>
</html>
