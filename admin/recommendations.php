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
        <title>LMS - Book Recommendations</title>
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
                <!-- Display message if set -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-info"><?php echo $_SESSION['message']; ?></div>
                    <?php unset($_SESSION['message']); // Clear the message after displaying it ?>
                <?php endif; ?>

                <h2>Book Recommendations</h2>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Book Name</th>
                            <th>Description</th>
                            <th>Recommended By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM LMS.recommendations";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            $bookname = $row['Book_Name'];
                            $description = $row['Description'];
                            $idno = $row['IDNo'];
                            $recommendationId = $row['R_ID']; // Get the ID for removal
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($bookname); ?></td>
                                <td><?php echo htmlspecialchars($description); ?></td>
                                <td><b><?php echo strtoupper(htmlspecialchars($idno)); ?></b></td>
                                <td>
                                    <form method="post" action="">
                                        <input type="hidden" name="id" value="<?php echo $recommendationId; ?>">
                                        <input type="submit" name="remove" value="Remove" class="btn btn-danger">
                                    </form>
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
