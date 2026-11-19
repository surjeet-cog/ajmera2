<?php
require_once '../dbc.php'; // Apne database se connect karein

// Naya password (admin123)
$password = 'admin123';

// Password ko secure hash me convert karein
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Database me update karein
$query = "UPDATE admin_users SET password = '$hashed_password' WHERE username = 'admin'";

if ($conn->query($query)) {
    echo "<h3 style='color:green;'>Password updated successfully!</h3>";
    echo "<p>Now go to <a href='index.php'>Login Page</a> and login with password: <b>admin123</b></p>";
} else {
    echo "Error updating password: " . $conn->error;
}
?>