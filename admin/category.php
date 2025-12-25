<?php
include 'connection.php';
session_start();

// Fetch distinct categories from the services table
$sql = "SELECT DISTINCT service_title FROM services";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Categories</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Basic styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
        .categories-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .category-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease-in-out;
            text-align: center;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .category-card:hover {
            transform: translateY(-10px);
        }
        .category-card .icon {
            font-size: 48px;
            color: #007bff;
            margin-bottom: 15px;
        }
        .category-card h3 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .category-card p {
            font-size: 16px;
            color: #777;
        }
        .category-card .no-service-message {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 10px;
            border-radius: 5px;
        }
        .category-card.no-service .no-service-message {
            display: block;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Service Categories</h1>
    <div class="categories-wrapper">
        <?php
        // Check if categories exist
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $category = htmlspecialchars($row['service_title']);
                
                // Check if providers are available for the category
                $provider_check_sql = "SELECT COUNT(*) AS provider_count FROM services WHERE service_title = ?";
                $provider_check_stmt = $conn->prepare($provider_check_sql);
                $provider_check_stmt->bind_param("s", $category);
                $provider_check_stmt->execute();
                $provider_check_result = $provider_check_stmt->get_result();
                $provider_check_row = $provider_check_result->fetch_assoc();
                $provider_count = $provider_check_row['provider_count'];
                
                // Add category card
                echo "<div class='category-card " . ($provider_count == 0 ? 'no-service' : '') . "' onclick='checkServiceProvider(\"$category\", $provider_count)'>";
                echo "<div class='icon'><i class='fas fa-concierge-bell'></i></div>";
                echo "<h3>" . $category . "</h3>";
                echo "<p>" . ($provider_count == 0 ? "No providers available" : "Click to view providers") . "</p>";
                echo "<div class='no-service-message'>No service providers available in this category.</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No categories available.</p>";
        }

        $conn->close();
        ?>
    </div>
</div>

<script>
    // Function to check if providers exist and redirect if not
    function checkServiceProvider(category, providerCount) {
        if (providerCount === 0) {
            alert('No service providers available in this category.');
            setTimeout(function() {
                window.location.href = 'category.php';  // Redirect to category page after 1 second
            }, 1000);
        } else {
            window.location.href = 'services_by_category.php?service_title=' + encodeURIComponent(category);
        }
    }
</script>

</body>
</html>
