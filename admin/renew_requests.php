<?php
require('dbconn.php');

// Check if user is logged in
if (isset($_SESSION['IDNo'])) {
    // Check for a notification message in the URL
    $notification = isset($_GET['notification']) ? htmlspecialchars($_GET['notification']) : '';
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/sidebar.css">
        <title>LMS - Renew Requests</title>
        <style>
            /* Main content area */
            .content {
                flex-grow: 1;
                display: flex;
                justify-content: center;
                align-items: flex-start; /* Align items to the start */
                background-color: #f8f9fa; /* Light background for the main content */
                padding: 20px;
                margin-left: 250px; /* Space for sidebar */
                overflow-y: auto; /* Allow scrolling if content exceeds */
            }

            /* Notification styling */
            .notification {
                margin-bottom: 20px;
                padding: 10px;
                border-radius: 5px;
                color: #fff;
            }

            .success {
                background-color: #28a745; /* Green */
            }

            .error {
                background-color: #dc3545; /* Red */
            }

            /* Table styling */
            .table-container {
                width: 100%; /* Full width for the table */
                padding: 20px;
                border-radius: 10px;
                background-color: white;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }

            table {
                width: 100%; /* Full width for the table */
                border-collapse: collapse; /* Collapse borders */
            }

            th, td {
                padding: 10px;
                text-align: left;
                border: 1px solid #dee2e6; /* Add border between columns */
            }

            th {
                background-color: #f1f1f1; /* Header background color */
                font-weight: bold; /* Bold text for headers */
            }

            tr:hover {
                background-color: #f1f1f1; /* Highlight row on hover */
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
                background-color: #28a745; /* Green */
            }

            .btn-success:hover {
                background-color: #218838; /* Darker green on hover */
                transform: scale(1.05); /* Slightly enlarge on hover */
            }

            .btn-danger {
                background-color: #dc3545; /* Red */
            }

            .btn-danger:hover {
                background-color: #c82333; /* Darker red on hover */
                transform: scale(1.05); /* Slightly enlarge on hover */
            }

            .btn-info {
                background-color: #17a2b8; /* Teal */
            }

            .btn-info:hover {
                background-color: #138496; /* Darker teal on hover */
                transform: scale(1.05); /* Slightly enlarge on hover */
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

                <h1><i>Renew Requests</i></h1>

                <!-- Notification Section -->
                <?php if ($notification): ?>
                    <div class="notification <?php echo strpos($notification, 'success') !== false ? 'success' : 'error'; ?>">
                        <?php echo $notification; ?>
                    </div>
                <?php endif; ?>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>ID Number</th>
                            <th>Book Id</th>
                            <th>Book Name</th>
                            <th>Renewals Left</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Updated SQL query to use the 'user' table
                        $sql = "SELECT renew.IDNo, user.Name, renew.BookId, book.Title, record.Renewals_left 
                                FROM LMS.renew 
                                JOIN LMS.record ON renew.IDNo = record.IDNo AND renew.BookId = record.BookId 
                                JOIN LMS.book ON renew.BookId = book.BookId
                                JOIN LMS.user ON renew.IDNo = user.IDNo 
                                GROUP BY renew.IDNo, renew.BookId"; // Grouping to remove duplicates
                        $result = $conn->query($sql);
                        
                        // Fetch results and display them
                        while ($row = $result->fetch_assoc()) {
                            $bookid = $row['BookId'];
                            $idno = $row['IDNo'];
                            $name = $row['Name'];
                            $renewals = $row['Renewals_left'];
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($name); ?></td>
                                <td><?php echo strtoupper($idno); ?></td>
                                <td><?php echo $bookid; ?></td>
                                <td><b><?php echo htmlspecialchars($row['Title']); ?></b></td>
                                <td><?php echo $renewals; ?></td>
                                <td>
                                    <center>
                                        <?php
                                        if ($renewals > 0) {
                                            echo "<a href=\"acceptrenewal.php?id1=" . $bookid . "&id2=" . $idno . "\" class=\"btn btn-success\">Accept</a>";
                                            echo " <a href=\"reject.php?id1=" . $bookid . "&id2=" . $idno . "\" class=\"btn btn-danger\">Reject</a>"; // Updated Reject button
                                        }
                                        ?>
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
    // Redirect to an error page or display an error message
    header("Location: error.php?message=Access Denied");
    exit();
}
?>
