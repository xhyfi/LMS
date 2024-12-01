<?php
require('dbconn.php');
session_start(); // Start session for notifications

$bookid = $_GET['id1'];
$idno = $_GET['id2'];

$sql = "DELETE FROM LMS.record WHERE IDNo = '$idno' AND BookId = '$bookid'";

if ($conn->query($sql) === TRUE) {
    // Insert a message into the LMS.message table
    $sql1 = "INSERT INTO LMS.message (IDNo, Msg, Date, Time) 
             VALUES ('$idno', 'Your request for issue of BookId: $bookid has been rejected', CURDATE(), CURTIME())";
    $conn->query($sql1);

    // Set a success message for the session
    $_SESSION['message'] = 'Your request for BookId: ' . $bookid . ' has been rejected.';
    header("Location: issue_requests.php");
    exit();
} else {
    // Set an error message for the session
    $_SESSION['message'] = 'Error occurred while rejecting the request.';
    header("Location: issue_requests.php");
    exit();
}
?>
