<?php
// Database credentials
$servername = "localhost"; // Default server name
$username = "root"; // Default username
$password = ""; // Default password (usually empty for local setups)
$database = "local"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connection successful

// You can now use $conn to interact with your database

// Example: Run a query
// $result = $conn->query("SELECT * FROM table_name");

// Close the connection when done
// $conn->close();
?>
