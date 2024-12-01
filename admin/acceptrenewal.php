<?php
require('dbconn.php');

$bookid = $_GET['id1'];
$idno = $_GET['id2'];

// Update the LMS.record table to extend the Due Date and set Renewals_left to 0
$sql1 = "UPDATE LMS.record 
         SET Due_Date = DATE_ADD(Due_Date, INTERVAL 3 DAY), 
             Renewals_left = 0 
         WHERE BookId = '$bookid' AND IDNo = '$idno'";

if ($conn->query($sql1) === TRUE) {
    // Delete from LMS.renew table as renewal is processed
    $sql3 = "DELETE FROM LMS.renew WHERE BookId = '$bookid' AND IDNo = '$idno'";
    $conn->query($sql3);

    // Insert a message for the user about the renewal success
    $sql5 = "INSERT INTO LMS.message (IDNo, Msg, Date, Time) 
             VALUES ('$idno', 'Your request for renewal of BookId: $bookid has been accepted', CURDATE(), CURTIME())";
    $conn->query($sql5);

    // Redirect with success message
    header("Location: renew_requests.php?status=success");
    exit();
} else {
    // Redirect with error message if renewal process fails
    header("Location: renew_requests.php?status=error");
    exit();
}

?>
