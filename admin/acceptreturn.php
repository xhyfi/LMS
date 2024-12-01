<?php
require('dbconn.php');

$bookid = $_GET['id1'];
$idno = $_GET['id2'];
$dues = $_GET['id3'];

// Update the record to set Date of Return and Dues
$sql1 = "UPDATE LMS.record 
         SET Date_of_Return = CURDATE(), 
             Dues = '$dues' 
         WHERE BookId = '$bookid' AND IDNo = '$idno'";

if ($conn->query($sql1) === TRUE) {
    // Increase availability of the book
    $sql3 = "UPDATE LMS.book 
             SET Availability = Availability + 1 
             WHERE BookId = '$bookid'";
    $result = $conn->query($sql3);

    // Delete the return record from LMS.return table
    $sql4 = "DELETE FROM LMS.return 
             WHERE BookId = '$bookid' AND IDNo = '$idno'";
    $result = $conn->query($sql4);

    // Delete the renew record from LMS.renew table
    $sql6 = "DELETE FROM LMS.renew 
             WHERE BookId = '$bookid' AND IDNo = '$idno'";
    $result = $conn->query($sql6);

    // Insert a message into the LMS.message table
    $sql5 = "INSERT INTO LMS.message (IDNo, Msg, Date, Time) 
             VALUES ('$idno', 'Your request for return of BookId: $bookid has been accepted', CURDATE(), CURTIME())";
    $result = $conn->query($sql5);

    // Redirect with success message
    header("Location: return_requests.php?status=success");
    exit();
} else {
    // Redirect with error message
    header("Location: return_requests.php?status=error");
    exit();
}
?>
