<?php
require('dbconn.php');

// Check if IDNo is set in the session
if (!isset($_SESSION['IDNo'])) {
    $_SESSION['message'] = 'Access Denied!'; // Handle access without a valid session
    $_SESSION['msg_type'] = 'danger'; // Message type for custom styling
    header("Location: index.php", true, 303);
    exit();
}

// Fetch the list of currently borrowed books from the 'record' table
$sql = "SELECT b.Title, r.BookId, r.Date_of_Issue, r.Due_Date, 
        CASE WHEN CURDATE() > r.Due_Date THEN 'Yes' ELSE 'No' END AS Overdue
        FROM LMS.record r 
        JOIN LMS.book b ON r.BookId = b.BookId 
        WHERE r.IDNo = ? AND r.Date_of_Return IS NULL"; // Only get unreturned books
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['IDNo']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Loans</title>
    <link rel="stylesheet" href="css/sidebar.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            display: flex; /* Added flex display for sidebar layout */
        }
        /* Sidebar styling */
        .sidebar {
            position: fixed;
            width: 250px;
            height: 100%;
            background-color: #343a40;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        /* Sidebar button styling */
        .sidebar button {
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            text-align: left;
            width: 100%;
            padding: 10px;
            cursor: pointer;
        }
        .sidebar button:hover {
            background-color: #495057;
        }
        .divider {
            height: 1px;
            background-color: #495057;
            margin: 10px 0; /* Space between buttons */
        }
        /* Main content area */
        .container {
            margin-left: 250px; /* Space for sidebar */
            padding: 20px;
            flex-grow: 1; /* Take up remaining space */
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .notification {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .card {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 600px; /* Adjust the width as needed */
            margin: auto; /* Center the card */
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-button {
            background-color: #28a745; /* Green color */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .action-button:hover {
            background-color: #218838; /* Darker green */
        }
        .renew-button {
            background-color: #007bff; /* Blue color */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .renew-button:hover {
            background-color: #0069d9; /* Darker blue */
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
        <button onclick="location.href='book.php'">All Books</button>
        <div class="divider"></div>
        <button onclick="location.href='history.php'">Previously Borrowed Books</button>
        <div class="divider"></div>
        <button onclick="location.href='recommendations.php'">Recommend Books</button>
        <div class="divider"></div>
        <button onclick="location.href='current.php'">Currently Issued Books</button>
        <div class="divider"></div>
        <button onclick="location.href='logout.php'" style="margin-top: auto;">Logout</button>
    </div>

    <div class="container">
        <!-- Notification Card -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="notification">
                <div class="card">
                    <div class="alert <?php echo $_SESSION['msg_type']; ?>">
                        <?php
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <h2>Current Loans</h2>
        
        <!-- Main Content Card -->
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Book ID</th>
                        <th>Title</th>
                        <th>Date Issued</th>
                        <th>Due Date</th>
                        <th>Overdue</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are any current loans
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $bookid = $row['BookId'];
                            $name = $row['Title'];
                            $issuedate = $row['Date_of_Issue'];
                            $duedate = $row['Due_Date'];
                            $isOverdue = $row['Overdue'] === 'Yes';

                            // Calculate fine if overdue
                            $fine = 0;
                            if ($isOverdue) {
                                $overdueDays = (strtotime(date("Y-m-d")) - strtotime($duedate)) / (60 * 60 * 24);
                                $fine = min(2500, ceil($overdueDays / 2) * 500);
                            }
                    ?>
                    <tr>
                        <td><?php echo $bookid; ?></td>
                        <td><?php echo htmlspecialchars($name); ?></td>
                        <td><?php echo htmlspecialchars($issuedate); ?></td>
                        <td><?php echo htmlspecialchars($duedate); ?></td>
                        <td><?php echo $isOverdue ? '<span style="color:red;">Yes</span>' : '<span style="color:green;">No</span>'; ?></td>
                        <td>
                            <?php if ($isOverdue): ?>
                                <form method="POST" action="payment.php" style="display:inline;">
                                    <input type="hidden" name="bookid" value="<?php echo $bookid; ?>">
                                    <input type="hidden" name="fine" value="<?php echo $fine; ?>">
                                    <button type="submit" name="pay_return" class="action-button">Pay Fine</button>
                                </form>
                            <?php else: ?>
                                <form method="GET" action="return_request.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $bookid; ?>">
                                    <button type="submit" class="action-button">Return</button>
                                </form>
                                <form method="GET" action="renew_request.php" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $bookid; ?>">
                                    <button type="submit" class="renew-button">Renew</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        // Message if no current loans
                        echo '<tr><td colspan="6">No currently borrowed books.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
