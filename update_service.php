<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "local";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $service_title = $_POST['service_title'];
    $service_description = $_POST['service_description'];
    $contact_email = $_POST['contact_email'];
    $contact_phone = $_POST['contact_phone'];

    $sql = "UPDATE services SET service_title=?, service_description=?, contact_email=?, contact_phone=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $service_title, $service_description, $contact_email, $contact_phone, $id);

    if ($stmt->execute()) {
        // Check if a new image was uploaded
        if (!empty($_FILES['service_image']['name'])) {
            $service_image = $_FILES['service_image']['name'];
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($service_image);

            // Move uploaded image to the directory
            if (move_uploaded_file($_FILES["service_image"]["tmp_name"], $target_file)) {
                $sql = "UPDATE services SET service_image=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $service_image, $id);
                $stmt->execute();
            }
        }

        echo "<script>alert('Service updated successfully!');
        window.location.href = 'your_service.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
