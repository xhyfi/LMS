<?php
require('dbconn.php');
session_start(); // Start session for notifications

$bookid = $_GET['id1'];
$idno = $_GET['id2'];

// Update the record to set Date of Issue, Due Date, and Renewals left
$sql1 = "UPDATE LMS.record 
         SET Date_of_Issue = CURDATE(), 
             Due_Date = DATE_ADD(CURDATE(), INTERVAL 3 DAY), 
             Renewals_left = 1 
         WHERE BookId = '$bookid' AND IDNo = '$idno'";

if ($conn->query($sql1) === TRUE) {
    // Decrease availability of the book
    $sql3 = "UPDATE LMS.book 
             SET Availability = Availability - 1 
             WHERE BookId = '$bookid'";
    $conn->query($sql3);

    // Insert a message into the LMS.message table
    $sql5 = "INSERT INTO LMS.message (IDNo, Msg, Date, Time) 
             VALUES ('$idno', 'Your request for issue of BookId: $bookid has been accepted', CURDATE(), CURTIME())";
    $conn->query($sql5);

    // Set a success message for the session
    $_SESSION['message'] = 'Your request for BookId: ' . $bookid . ' has been accepted.';
    header("Location: issue_requests.php");
    exit();
} else {
    // Set an error message for the session
    $_SESSION['message'] = 'Error occurred while accepting the request.';
    header("Location: issue_requests.php");
    exit();
}
?>
