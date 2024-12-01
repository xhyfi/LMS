<?php
require('dbconn.php');

// Check if user is logged in
if (isset($_SESSION['IDNo'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/sidebar.css">
        <title>LMS - Overdue Books Record</title>
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
                <h2>Overdue Books Record</h2>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Total Overdue Books</th>
                            <th>Total Penalty (PHP)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $currentDate = date('Y-m-d');
                        $penaltyPerBook = 500; // Penalty for each overdue book

                        $sql = "SELECT u.IDNo, u.Name, COUNT(r.BookId) AS overdue_count
                                FROM user u
                                JOIN record r ON u.IDNo = r.IDNo
                                WHERE r.Due_Date < '$currentDate' AND r.Date_of_Return IS NULL
                                GROUP BY u.IDNo";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $overdueCount = $row['overdue_count'];
                                $totalPenalty = $overdueCount * $penaltyPerBook;

                                echo "<tr>
                                        <td>" . htmlspecialchars($row['IDNo']) . "</td>
                                        <td>" . htmlspecialchars($row['Name']) . "</td>
                                        <td>" . htmlspecialchars($overdueCount) . "</td>
                                        <td>" . htmlspecialchars($totalPenalty) . "</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No overdue books found.</td></tr>";
                        }
                        $result->free();
                        ?>
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
