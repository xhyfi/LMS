<?php
require('dbconn.php');

// Check if user is logged in
if (isset($_SESSION['IDNo'])) {
    if (isset($_GET['id1']) && isset($_GET['id2']) && isset($_GET['id3'])) {
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
            $conn->query($sql3);

            // Delete the return record from LMS.return table
            $sql4 = "DELETE FROM LMS.return 
                     WHERE BookId = '$bookid' AND IDNo = '$idno'";
            $conn->query($sql4);

            // Delete the renew record from LMS.renew table
            $sql6 = "DELETE FROM LMS.renew 
                     WHERE BookId = '$bookid' AND IDNo = '$idno'";
            $conn->query($sql6);

            // Insert a message into the LMS.message table
            $sql5 = "INSERT INTO LMS.message (IDNo, Msg, Date, Time) 
                     VALUES ('$idno', 'Your request for return of BookId: $bookid has been accepted', CURDATE(), CURTIME())";
            $conn->query($sql5);

            // Set success message in session
            $_SESSION['message'] = 'Book return request was successful.';
            $_SESSION['msg_type'] = 'success';
        } else {
            // Set error message in session
            $_SESSION['message'] = 'There was an error processing the return request.';
            $_SESSION['msg_type'] = 'danger';
        }

        // Redirect to the same page
        header("Location: return_requests.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sidebar.css">
    <title>LMS - Return Requests</title>
    <style>
        /* Main content area */
        .content {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background-color: #f8f9fa;
            padding: 20px;
            margin-left: 250px;
            overflow-y: auto;
        }

        /* Table styling */
        .table-container {
            width: 100%;
            padding: 20px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .btn-info {
            background-color: #17a2b8;
        }

        .btn-info:hover {
            background-color: #138496;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
            <h1>LMS</h1>
            <button onclick="location.href='index.php'">Home</button>
            <div class="divider"></div>
            <button onclick="location.href='message.php'">Messages</button>
            <div class="divider"></div>
            <button onclick="location.href='register.php'">Register Student</button>
            <div class="divider"></div>
            <button onclick="location.href='student.php'">Manage Students</button>
            <div class="divider"></div>
            <button onclick="location.href='record.php'">Records</button>
            <div class="divider"></div>
            <button onclick="location.href='book.php'">All Books</button>
            <div class="divider"></div>
            <button onclick="location.href='addbook.php'">Add Books</button>
            <div class="divider"></div>
            <button onclick="location.href='requests.php'">Issue/Return Requests</button>
            <div class="divider"></div>
            <button onclick="location.href='recommendations.php'">Book Recommendations</button>
            <div class="divider"></div>
            <button onclick="location.href='current.php'">Currently Issued Books</button>
            <div class="divider"></div>
            <button onclick="location.href='transaction.php'">Transactions</button>
            <div class="divider"></div>
            <button onclick="location.href='logout.php'" style="margin-bottom: 20px;">Logout</button> <!-- Added margin -->
        </div>

    <!-- Main Content Area -->
    <div class="content">
        <div class="table-container">
            <center>
                <a href="issue_requests.php" class="btn btn-info">Issue Requests</a>
                <a href="renew_requests.php" class="btn btn-info">Renew Request</a>
                <a href="return_requests.php" class="btn btn-info">Return Requests</a>
            </center>

            <h1><i>Return Requests</i></h1>

            <!-- Display success/error message if any -->
            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='alert alert-" . $_SESSION['msg_type'] . "'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']); // Clear the message after displaying it
                unset($_SESSION['msg_type']); // Clear the message type after displaying it
            }
            ?>

            <table class="table" id="tables">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>ID Number</th>
                        <th>Book Id</th>
                        <th>Book Name</th>
                        <th>Dues</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT return.BookId, return.IDNo, Title, DATEDIFF(CURDATE(), Due_Date) AS x, user.name AS StudentName
                        FROM LMS.return
                        JOIN LMS.book ON return.BookId = book.BookId
                        JOIN LMS.record ON return.BookId = record.BookId AND return.IDNo = record.IDNo
                        JOIN LMS.user ON return.IDNo = user.IDNo
                        GROUP BY return.BookId, return.IDNo";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    $bookid = $row['BookId'];
                    $idno = $row['IDNo'];
                    $name = $row['Title'];
                    $dues = $row['x'];
                    $studentName = $row['StudentName'];
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($studentName); ?></td>
                        <td><?php echo strtoupper($idno); ?></td>
                        <td><?php echo $bookid; ?></td>
                        <td><b><?php echo htmlspecialchars($name); ?></b></td>
                        <td><?php echo $dues > 0 ? $dues : 0; ?></td>
                        <td>
                            <center>
                                <a href="return_requests.php?id1=<?php echo $bookid; ?>&id2=<?php echo $idno; ?>&id3=<?php echo $dues; ?>" class="btn btn-success">Accept</a>
                            </center>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>

<?php
} else {
    // PHP redirection to another page if access is denied
    header("Location: access_denied.php");
    exit();
}
?>
