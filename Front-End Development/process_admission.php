<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "localhost";
$username = "root@localhost ";
$password = "root";
$dbname = "Alliance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug: Print all received POST data
    echo "<h2>Received POST data:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Collect form data
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
    $contact = isset($_POST['contact']) ? $conn->real_escape_string($_POST['contact']) : '';
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
    $course = isset($_POST['course']) ? $conn->real_escape_string($_POST['course']) : '';
    $address = isset($_POST['address']) ? $conn->real_escape_string($_POST['address']) : '';
    $x_percentage = isset($_POST['x-percentage']) ? floatval($_POST['x-percentage']) : 0;
    $xii_percentage = isset($_POST['xii-percentage']) ? floatval($_POST['xii-percentage']) : 0;

    // Prepare SQL statement
    $sql = "INSERT INTO admissions (name, contact, email, course, address, x_percentage, xii_percentage) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Create a prepared statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters to the prepared statement
        $stmt->bind_param("sssssdd", $name, $contact, $email, $course, $address, $x_percentage, $xii_percentage);

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo "<h2>Application submitted successfully</h2>";
        } else {
            echo "<h2>Error: " . $stmt->error . "</h2>";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "<h2>Error in preparing statement: " . $conn->error . "</h2>";
    }
} else {
    echo "<h2>No POST data received. Please submit the form.</h2>";
}

// Close the database connection
$conn->close();