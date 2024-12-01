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
        <title>LMS - Manage Students</title>
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

            /* Notification styles */
            .alert {
                padding: 10px;
                margin-bottom: 20px;
                background-color: #f1f1f1;
                color: #333;
                border-radius: 5px;
                text-align: center;
            }

            /* Button styling */
            .btn {
                padding: 10px 15px; /* Padding for buttons */
                border: none; /* Remove border */
                border-radius: 5px; /* Rounded corners */
                color: white; /* Text color */
                font-size: 14px; /* Font size */
                cursor: pointer; /* Pointer cursor on hover */
                text-decoration: none; /* No underline for links */
                transition: background-color 0.3s, transform 0.2s; /* Smooth transition for background and scaling */
                margin: 5px; /* Margin for spacing */
            }

            .btn-success {
                background-color: #28a745; /* Green */
            }

            .btn-success:hover {
                background-color: #218838; /* Darker green on hover */
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
                <!-- Display Notification -->
                <?php
                if (isset($_SESSION['message'])) {
                    echo "<div class='alert alert-info'>" . $_SESSION['message'] . "</div>";
                    unset($_SESSION['message']);
                }
                ?>

                <h2>Manage Students</h2>
                <form action="student.php" method="post">
                    <div class="form-group">
                        <label for="title"><b>Search:</b></label>
                        <input type="text" id="title" name="title" placeholder="Enter Name/Roll No of Student" required>
                        <button type="submit" name="submit" class="btn btn-info">Search</button>
                    </div>
                </form>
                <br>

                <!-- Search Results or Full Student List -->
                <?php
                if (isset($_POST['submit'])) {
                    $s = $_POST['title'];
                    $sql = "SELECT * FROM LMS.user WHERE (IDNo='$s' OR Name LIKE '%$s%') AND IDNo <> 'ADMIN'";
                } else {
                    $sql = "SELECT * FROM LMS.user WHERE IDNo <> 'ADMIN'";
                }

                $result = $conn->query($sql);
                $rowcount = mysqli_num_rows($result);

                if ($rowcount == 0) {
                    echo "<br><center><h2><b><i>No Results</i></b></h2></center>";
                } else {
                    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>ID No.</th>
                                <th>Email id</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $result->fetch_assoc()) {
                                $email = $row['EmailId'];
                                $name = $row['Name'];
                                $idno = $row['IDNo'];
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($name); ?></td>
                                    <td><?php echo htmlspecialchars($idno); ?></td>
                                    <td><?php echo htmlspecialchars($email); ?></td>
                                    <td>
                                        <center>
                                            <a href='studentdetails.php?id=<?php echo htmlspecialchars($idno); ?>' class='btn btn-success'>Details</a>
                                            <a href='student_record.php?id=<?php echo htmlspecialchars($idno); ?>' class='btn btn-info'>Records</a>
                                        </center>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>

    </body>

    </html>

    <?php
} else {
    header("Location: error.php?message=Access Denied");
    exit();
}
?>
