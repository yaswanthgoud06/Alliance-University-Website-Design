<?php
// Database connection parameters
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If connection is successful
echo "Connected successfully";

// Function to sanitize user input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Example of how to use the connection for a login query
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $userid = sanitize_input($_POST['userid']);
    $password = sanitize_input($_POST['password']);
    
    $sql = "SELECT * FROM users WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo "Login successful";
            // Start session, set session variables, redirect, etc.
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }
    
    $stmt->close();
}

// Close the connection when you're done
$conn->close();
