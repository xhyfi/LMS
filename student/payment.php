<?php
require('dbconn.php');

// Check if IDNo is set in the session
if (!isset($_SESSION['IDNo'])) {
    $_SESSION['message'] = 'Access Denied!';
    $_SESSION['msg_type'] = 'danger';
    header("Location: index.php", true, 303);
    exit();
}

// Initialize variables
$bookid = null;
$fine = 0.00;
$userId = $_SESSION['IDNo'];

// Get book information from the record table
$recordSql = "SELECT BookId, Due_Date, Dues FROM LMS.record WHERE IDNo = ?";
$recordStmt = $conn->prepare($recordSql);
$recordStmt->bind_param("s", $userId);
$recordStmt->execute();
$recordResult = $recordStmt->get_result();

if ($recordResult->num_rows > 0) {
    // Fetch the first record
    $record = $recordResult->fetch_assoc();
    $bookid = $record['BookId'];
    $dueDate = $record['Due_Date'];

    // Calculate the fine
    $baseFine = 500;
    $maxFine = 2500;
    $currentDate = new DateTime(); // Current date
    $dueDateTime = new DateTime($dueDate); // Convert due date from DB to DateTime

    // Calculate the number of days past due date
    $dateDifference = $currentDate->diff($dueDateTime)->days;
    if ($dateDifference > 0) {
        // Calculate the number of 2-day periods past due
        $finePeriods = floor($dateDifference / 2);
        // Calculate the fine
        $fine = min($baseFine + ($finePeriods * 500), $maxFine);
    }

} else {
    $_SESSION['message'] = 'No records found for this user.';
    $_SESSION['msg_type'] = 'danger';
    header("Location: current.php", true, 303);
    exit();
}

// Process payment if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    // Insert the payment record
    $sql = "INSERT INTO LMS.payments (user_id, book_id, amount, payment_date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iid", $userId, $bookid, $fine);

    if ($stmt->execute()) {
        // Get the last inserted payment ID (transaction ID)
        $transactionId = $conn->insert_id; // Get the last inserted ID

        // Update the record table to return the book and set dues to zero
        $updateSql = "UPDATE LMS.record SET Date_of_Return = NOW(), Dues = 0 WHERE IDNo = ? AND BookId = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $userId, $bookid);
        $updateStmt->execute();

        // Remove the book from the record table
        $deleteSql = "DELETE FROM LMS.record WHERE IDNo = ? AND BookId = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bind_param("si", $userId, $bookid);
        $deleteStmt->execute();

        $_SESSION['message'] = 'Payment Successful. Amount Paid: ' . number_format($fine, 2) . ' PHP. Transaction ID: ' . htmlspecialchars($transactionId) . '. Book returned and removed successfully.';
        $_SESSION['msg_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error processing payment: ' . $stmt->error;
        $_SESSION['msg_type'] = 'danger';
    }

    header("Location: current.php", true, 303);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <link rel="stylesheet" href="css/sidebar.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .card {
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 50px;
        }
        .action-button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
        }
        .action-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Confirm Payment</h2>
            <p>You are about to pay a fine for Book ID: <strong><?php echo htmlspecialchars($bookid); ?></strong></p>
            <p>Fine Amount: <strong><?php echo number_format($fine, 2); ?> PHP</strong></p>

            <form method="POST" action="payment.php">
                <input type="hidden" name="bookid" value="<?php echo htmlspecialchars($bookid); ?>">
                <input type="hidden" name="fine" value="<?php echo $fine; ?>">
                <button type="submit" name="confirm_payment" class="action-button">Confirm Payment</button>
            </form>
        </div>
    </div>
</body>
</html>
