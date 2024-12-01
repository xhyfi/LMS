<?php
require('dbconn.php');
session_start(); // Start session to access session variables

// Check if the ID parameter is set
if (!isset($_GET['id'])) {
    $_SESSION['message'] = 'Invalid request. Book ID not specified.';
    $_SESSION['msg_type'] = 'error';
    header("Location: book.php");
    exit();
}

$id = $_GET['id']; // Book ID
$roll = $_SESSION['IDNo']; // Student ID

// Check if the user has the specific book currently issued
$sql_check = "SELECT * FROM LMS.record WHERE IDNo = '$roll' AND BookId = '$id' AND Date_of_Return IS NULL";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    // User already has this book issued
    $_SESSION['message'] = 'You already have this book issued. Please return it before issuing again.';
    $_SESSION['msg_type'] = 'error';
    header("Location: book.php");
    exit();
} else {
    // Count how many books the user has currently issued
    $sql_count = "SELECT COUNT(*) as total FROM LMS.record WHERE IDNo = '$roll' AND Date_of_Return IS NULL";
    $result_count = $conn->query($sql_count);
    $row_count = $result_count->fetch_assoc();

    if ($row_count['total'] >= 5) {
        // User has already issued the maximum number of books
        $_SESSION['message'] = 'You can only issue up to 5 books at a time.';
        $_SESSION['msg_type'] = 'error';
        header("Location: book.php");
        exit();
    } else {
        // Check if the book is available for issuance
        $sql_availability = "SELECT Availability FROM LMS.book WHERE BookId = '$id'";
        $result_availability = $conn->query($sql_availability);
        $book_row = $result_availability->fetch_assoc();

        if ($book_row && $book_row['Availability'] > 0) {
            // Insert a new record for the book issuance
            $sql = "INSERT INTO LMS.record (IDNo, BookId, Time) VALUES ('$roll', '$id', CURTIME())";
            
            if ($conn->query($sql) === TRUE) {
                // Request sent successfully
                $_SESSION['message'] = 'Book issued successfully!'; // Clarified success message
                $_SESSION['msg_type'] = 'success';
            } else {
                // If the insert fails
                $_SESSION['message'] = 'Error in issuing the book. Please try again later.';
                $_SESSION['msg_type'] = 'error';
            }
        } else {
            // Book is not available
            $_SESSION['message'] = 'Book is not available for issuance.';
            $_SESSION['msg_type'] = 'error';
        }

        header("Location: book.php");
        exit();
    }
}
?>
