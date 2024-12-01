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
        <title>LMS - Payment Transactions</title>
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

            .search-button {
                background-color: #007bff;
                color: white;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
                border-radius: 5px; /* Rounded corners */
            }

            .search-button:hover {
                background-color: #0056b3;
            }

            /* Adjust form layout */
            .form-inline {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                gap: 10px; /* Space between form elements */
                margin-bottom: 20px; /* Space below the form */
            }

            .form-group {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                padding: 10px; /* Added padding for spacing */
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
                <h2>Payment Transactions</h2>
                
                <div class="form-inline">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                    </div>
                    <button type="submit" name="filter" class="search-button">Filter</button>
                </div>

                <div class="divider"></div> <!-- Divider below search form -->

                <a href="print.php" target="_blank"><button class="search-button" style="margin-bottom: 20px;">Print</button></a>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Name</th>
                            <th>User ID</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query to retrieve payment information, joining with the user table
                        $sql = "SELECT p.*, u.Name FROM payments p JOIN user u ON p.user_id = u.IDNo WHERE 1=1";

                        // Add date filtering to SQL query if dates are provided
                        if (!empty($start_date)) {
                            $sql .= " AND payment_date >= '$start_date'";
                        }
                        if (!empty($end_date)) {
                            $sql .= " AND payment_date <= '$end_date'";
                        }

                        // Default sort order
                        $sql .= " ORDER BY payment_date DESC";

                        // Execute the query
                        if ($result = $conn->query($sql)) {
                            // Check if there are records
                            if ($result->num_rows > 0) {
                                // Fetch all payment records
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['amount']); ?></td>
                                        <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='5'>No transactions found.</td></tr>";
                            }
                            // Free the result set
                            $result->free();
                        } else {
                            echo "Error retrieving transactions: " . $conn->error;
                        }

                        // Close the database connection
                        $conn->close();
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
