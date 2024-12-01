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
        <title>LMS</title>
        <style>
            /* Main content area */
            .content {
                display: flex;
                justify-content: center; /* Center the cards horizontally */
                align-items: flex-start;
                background-color: #f0f0f0;
                padding: 20px;
                margin-left: 250px; /* Space for sidebar */
                overflow-y: auto; /* Allow scrolling if content exceeds */
                width: calc(100% - 250px); /* Adjust width to prevent overflow */
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

            /* Card styling */
            .card {
                border: 1px solid #ced4da;
                border-radius: 5px;
                padding: 20px;
                background-color: #ffffff;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 800px; /* Max width for the card */
                margin: 20px; /* Margin around the card */
            }

            /* Smaller Search Card */
            .search-card {
                padding: 10px; /* Reduced padding for the search card */
                margin: 0; /* No margin */
                max-width: 400px; /* Set a max width for the search card */
            }

            /* Table styling */
            .table {
                width: 100%;
                background-color: white;
                border-collapse: collapse;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            .table th, .table td {
                padding: 15px;
                text-align: left;
                border-bottom: 1px solid #dee2e6;
            }

            .table th {
                background-color: #f8f9fa;
            }

            .table tr:hover {
                background-color: #f1f1f1;
            }

            .alert {
                margin: 0; /* Remove margin */
                padding: 10px;
                border-radius: 5px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .notification {
                background-color: #f8f9fa; /* Default background */
                border: 1px solid #ced4da;
                border-radius: 5px;
                padding: 10px;
                width: 100%; /* Make notification fit inside card */
                box-sizing: border-box; /* Ensure padding does not increase width */
                margin-top: 10px; /* Space between search and notification */
            }

            /* Notification styles */
            .notification.success {
                background-color: #d4edda; /* Light green for success */
                border-color: #c3e6cb; /* Darker green for border */
            }

            .notification.error {
                background-color: #f8d7da; /* Light red for error */
                border-color: #f5c6cb; /* Darker red for border */
            }

            .form-horizontal {
                display: flex;
                justify-content: space-between; /* Space out the input and button */
                align-items: center;
            }

            .form-horizontal input {
                flex: 1; /* Allow the input to take available space */
                margin-right: 10px;
                max-width: 150px; /* Set a max width for the input */
            }

            /* Button styles */
            .btn {
                display: inline-block;
                padding: 8px 12px; /* Reduced padding for smaller button */
                border: none;
                border-radius: 5px;
                color: white;
                text-decoration: none;
                font-weight: bold;
                cursor: pointer;
                transition: background-color 0.3s;
                width: 80px; /* Set a fixed width for the button */
                text-align: center; /* Center text within button */
            }

            .btn-primary {
                background-color: #007bff; /* Primary button color */
            }

            .btn-success {
                background-color: #28a745; /* Green color for Issue button */
            }

            .btn-search {
                background-color: #17a2b8; /* Color for Search button */
            }

            .btn:hover {
                opacity: 0.9; /* Slightly fade on hover */
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

        <!-- Main Content Area -->
        <div class="content">
            <!-- Main Card -->
            <div class="card">
                <!-- Search Card -->
                <div class="search-card">
                    <form class="form-horizontal" action="book.php" method="post">
                        <label for="title"><b>Search:</b></label>
                        <input type="text" id="title" name="title" placeholder="Enter Name/ID of Book" required>
                        <button type="submit" name="submit" class="btn btn-search">Search</button>
                    </form>

                    <!-- Displaying Session Messages -->
                    <?php
                    if (isset($_SESSION['message'])) {
                        $msgType = $_SESSION['msg_type'] == 'success' ? 'success' : 'error'; // Determine type
                        echo '<div class="notification alert ' . $msgType . '">' . $_SESSION['message'] . '</div>';
                        unset($_SESSION['message']); // Clear the message after displaying it
                    }
                    ?>
                </div>

                <!-- Book Table -->
                <div class="table-container">
                    <?php
                    if (isset($_POST['submit'])) {
                        $s = $_POST['title'];
                        $sql = "SELECT * FROM LMS.book WHERE BookId='$s' OR Title LIKE '%$s%'";
                    } else {
                        $sql = "SELECT * FROM LMS.book ORDER BY Availability DESC";
                    }

                    $result = $conn->query($sql);
                    $rowcount = mysqli_num_rows($result);

                    if (!$rowcount) {
                        echo "<br><center><h2><b><i>No Results</i></b></h2></center>";
                    } else {
                        ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Book ID</th>
                                    <th>Book Name</th>
                                    <th>Availability</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = $result->fetch_assoc()) {
                                    $bookid = $row['BookId'];
                                    $name = $row['Title'];
                                    $avail = $row['Availability'];
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($bookid); ?></td>
                                        <td><?php echo htmlspecialchars($name); ?></td>
                                        <td><b><?php 
                                            echo $avail > 0 ? "<font color=\"green\">AVAILABLE</font>" : "<font color=\"red\">NOT AVAILABLE</font>";
                                        ?></b></td>
                                        <td>
                                            <center>
                                                <a href="bookdetails.php?id=<?php echo $bookid; ?>" class="btn btn-primary">Details</a>
                                                <?php
                                                if ($avail > 0) {
                                                    echo "<a href=\"issue_request.php?id=" . $bookid . "\" class=\"btn btn-success\">Issue</a>";
                                                }
                                                ?>
                                            </center>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
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
