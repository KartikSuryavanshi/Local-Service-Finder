<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "<script>alert('Please log in to add a service.'); window.location.href = '../login.php';</script>";
    exit();
}

$provider_email = $_SESSION['email']; // Get the provider's email from the session
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "local";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_title = $_POST['service_title'];
    $service_description = $_POST['service_description'];
    $service_image = $_FILES['service_image']['name'];
    $contact_email = $_POST['contact_email'];
    $contact_phone = $_POST['contact_phone'];

    // Check if uploads directory exists, if not create it
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Set the target file path
    $target_file = $target_dir . basename($service_image);

    // Validate the uploaded file
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($imageFileType, $allowedTypes)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit();
    }

    // Attempt to upload the file
    if (move_uploaded_file($_FILES["service_image"]["tmp_name"], $target_file)) {
        // Prepare the SQL insert statement
        $sql = "INSERT INTO services (provider_email, service_title, service_description, service_image, contact_email, contact_phone) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            // Bind parameters: s = string, s = string, s = string, s = string, s = string
            $stmt->bind_param("ssssss", $provider_email, $service_title, $service_description, $service_image, $contact_email, $contact_phone);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<script>alert('Service added successfully!');
                window.location.href = '../provider_dashboard.php';</script>";
            } else {
                echo "<p>Error: " . $stmt->error . "</p>";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "<p>Error preparing statement: " . $conn->error . "</p>";
        }
    } else {
        echo "Error uploading file: " . $_FILES["service_image"]["error"];
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2e6ff; /* Light purple background */
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #5f4b8b; /* Darker purple for headings */
        }
        form {
            max-width: 500px;
            margin: auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #5f4b8b; /* Dark purple color for labels */
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #d4a6ff; /* Light purple border */
            border-radius: 4px;
        }
        input[type="file"] {
            margin-bottom: 15px;
        }
        input[type="submit"] {
            background-color: #9c6ed5; /* Light purple button */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #7a4ab7; /* Darker purple for hover */
        }
         /* Light purple theme styling */
         body {
            font-family: Arial, sans-serif;
            background-color: #f2e6ff; /* Light purple background */
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #5f4b8b; /* Darker purple for headings */
        }
        form {
            max-width: 500px;
            margin: auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #5f4b8b; /* Dark purple color for labels */
            font-size: 1.1em;
        }
        input[type="text"],
        input[type="email"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #d4a6ff; /* Light purple border */
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
            background-color: #fafafa; /* Light background color */
            transition: all 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus,
        input[type="file"]:focus {
            border-color: #9c6ed5; /* Light purple focus border */
            background-color: #f1f0f5; /* Lighter background on focus */
            outline: none;
        }
        textarea {
            height: 150px; /* Set fixed height for textarea */
        }
        input[type="submit"] {
            background-color: #9c6ed5; /* Light purple button */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            width: 100%;
            transition: all 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #7a4ab7; /* Darker purple for hover */
        }
        /* General body styling */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f2e6ff; /* Light purple background */
    margin: 0;
    padding: 20px;
}

/* Heading styling */
h1 {
    text-align: center;
    color: #5f4b8b; /* Darker purple for headings */
    font-size: 2.5em;
    margin-bottom: 30px;
}

/* Form container styling */
form {
    max-width: 600px;
    margin: auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

/* Form label styling */
label {
    display: block;
    margin-bottom: 12px;
    color: #5f4b8b;
    font-size: 1.1em;
    font-weight: bold;
}

/* Input fields styling */
input[type="text"],
input[type="email"],
textarea,
input[type="file"] {
    width: 100%;
    padding: 14px;
    margin-bottom: 20px;
    border: 1px solid #d4a6ff; /* Light purple border */
    border-radius: 8px;
    font-size: 1em;
    background-color: #fafafa;
    box-sizing: border-box;
    transition: all 0.3s ease;
}

/* Input focus effect */
input[type="text"]:focus,
input[type="email"]:focus,
textarea:focus,
input[type="file"]:focus {
    border-color: #9c6ed5; /* Focus border */
    background-color: #f1f0f5;
    outline: none;
}

/* Textarea height */
textarea {
    height: 160px;
}

/* Submit button styling */
input[type="submit"] {
    background-color: #9c6ed5; /* Light purple button */
    color: white;
    padding: 14px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1.1em;
    width: 100%;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

/* Submit button hover effect */
input[type="submit"]:hover {
    background-color: #7a4ab7;
    transform: translateY(-2px);
}

/* Input field hover effect */
input[type="text"]:hover,
input[type="email"]:hover,
textarea:hover,
input[type="file"]:hover {
    background-color: #f9f9f9;
}

/* Responsive form adjustments */
@media (max-width: 600px) {
    form {
        padding: 20px;
    }

    h1 {
        font-size: 2em;
    }

    input[type="submit"] {
        padding: 12px 18px;
        font-size: 1em;
    }
}

    </style>
</head>
<body>
    <h1>Add New Service</h1>
    <form action="add_service.php" method="POST" enctype="multipart/form-data">
        <label for="service_title">Service Title:</label>
        <input type="text" name="service_title" required>

        <label for="service_description">Service Description:</label>
        <textarea name="service_description" required></textarea>

        <label for="service_image">Service Image:</label>
        <input type="file" name="service_image" accept="image/*" required>

        <label for="contact_email">Contact Email:</label>
        <input type="email" name="contact_email" required>

        <label for="contact_phone">Contact Phone:</label>
        <input type="text" name="contact_phone" required>

        <input type="submit" value="Add Service">
    </form>
</body>
</html>
