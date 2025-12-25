<?php
include 'connection.php';
session_start();

// Get the selected category from the query parameter
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch services that belong to the selected category
$sql = "SELECT service_title, provider_email FROM services";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services in Category: <?php echo htmlspecialchars($category); ?></title>
    <style>
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #007bff;
        }
        .service {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }
        .service h2 {
            margin: 0;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Services in Category: <?php echo htmlspecialchars($category); ?></h1>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='service'>";
            echo "<h2>" . htmlspecialchars($row['product_name']) . " (Provider ID: " . htmlspecialchars($row['provider_id']) . ")</h2>";
            echo "</div>";
        }
    } else {
        echo "<p>No services available in this category.</p>";
    }

    $stmt->close();
    $conn->close();
    ?>
</div>

</body>
</html>
