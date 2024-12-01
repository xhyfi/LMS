<?php
session_start();
$dbservername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "lms"; // Your database name

// Create connection
$conn = mysqli_connect($dbservername, $dbusername, $dbpassword);

// Check connection
if (!$conn) {
    echo "Connected unsuccessfully";
    die("Connection failed: " . mysqli_connect_error());
}

// Select the database
if (!mysqli_select_db($conn, $dbname)) {
    die("Database selection failed: " . mysqli_error($conn));
}
?>
