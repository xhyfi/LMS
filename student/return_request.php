<?php
require('dbconn.php');
session_start();

// Check if IDNo is set in the session
if (!isset($_SESSION['IDNo'])) {
    $_SESSION['message'] = 'Access Denied!'; // Handle access without a valid session
    $_SESSION['msg_type'] = 'danger'; // Message type for Bootstrap styling
    header("Location: current.php", true, 303);
    exit();
}

$id = $_GET['id'];
$roll = $_SESSION['IDNo'];

// Check if the return request already exists
$sql_check = "SELECT * FROM LMS.return WHERE IDNo = '$roll' AND BookId = '$id'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    // Entry already exists
    $_SESSION['message'] = 'Request Already Sent.';
    $_SESSION['msg_type'] = 'danger'; // Message type for Bootstrap styling
} else {
    // Insert the return request
    $sql = "INSERT INTO LMS.return (IDNo, BookId) VALUES ('$roll', '$id')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = 'Request Sent to Admin.';
        $_SESSION['msg_type'] = 'success'; // Message type for Bootstrap styling
    } else {
        // Handle potential SQL errors
        $_SESSION['message'] = 'Error in sending request: ' . $conn->error;
        $_SESSION['msg_type'] = 'danger'; // Message type for Bootstrap styling
    }
}

// Redirect to the current page
header("Location: current.php", true, 303);
exit(); // Exit to prevent further execution
?>
