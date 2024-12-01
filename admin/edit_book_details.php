<?php
ob_start();
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
        <title>Update Book Details - LMS</title>
        <style>
            /* Main content area */
            .content {
                flex-grow: 1;
                display: flex;
                flex-direction: column; /* Stack items vertically */
                align-items: center; /* Center items horizontally */
                background-color: #f0f0f0;
                padding: 20px;
                margin-left: 250px; /* Space for sidebar */
                overflow-y: auto; /* Allow scrolling if content exceeds */
                max-width: calc(100vw - 250px); /* Prevent overflow beyond the browser */
                box-sizing: border-box; /* Include padding in width calculation */
            }

            /* Sidebar styling */
           

           

            /* Module styling */
            .module {
                background-color: white; /* Card background */
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Shadow effect */
                padding: 40px; /* Padding inside card */
                width: 600px; /* Adjust width as necessary */
                border-radius: 10px; /* Rounded corners */
                transition: transform 0.3s; /* Smooth hover effect */
            }

            .module:hover {
                transform: scale(1.02); /* Scale up on hover */
            }

            .module-head h3 {
                margin: 0;
                padding-bottom: 20px;
                text-align: center; /* Center title */
                font-size: 1.8em; /* Larger font size for emphasis */
                color: #333;
            }

            .alert {
                margin-bottom: 20px;
                padding: 10px;
                border-radius: 4px;
                color: #fff;
            }

            .alert.alert-success {
                background-color: #28a745; /* Success color */
            }

            .alert.alert-danger {
                background-color: #dc3545; /* Error color */
            }

            .control-group {
                margin-bottom: 15px; /* Spacing between fields */
            }

            .control-label {
                font-weight: bold; /* Bold labels */
            }

            .controls {
                margin-left: 150px; /* Adjust margin for controls */
            }

            .btn {
                background-color: #007bff;
                color: white;
                border: none;
                padding: 10px 20px; /* Increased padding for buttons */
                border-radius: 4px;
                cursor: pointer;
                margin-top: 15px;
                font-size: 1em; /* Slightly larger button font */
                transition: background-color 0.3s, transform 0.2s;
                display: inline-block; /* Make it inline-block for better layout */
            }

            .btn:hover {
                background-color: #0056b3;
                transform: translateY(-2px); /* Subtle lift effect on hover */
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
            <div class="module">
                <div class="module-head">
                    <h3>Update Book Details</h3>
                </div>
                <div class="module-body">

                    <?php
                    $bookid = $_GET['id'];
                    $sql = "SELECT * FROM LMS.book WHERE BookId='$bookid'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $name = $row['Title'];
                    $publisher = $row['Publisher'];
                    $year = $row['Year'];
                    $avail = $row['Availability'];
                    ?>

                    <br>
                    <form action="edit_book_details.php?id=<?php echo $bookid ?>" method="post">
                        <div class="control-group">
                            <label class="control-label" for="Title">Book Title:</label>
                            <div class="controls">
                                <input type="text" id="Title" name="Title" value="<?php echo htmlspecialchars($name); ?>" required>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="Publisher">Publisher:</label>
                            <div class="controls">
                                <input type="text" id="Publisher" name="Publisher" value="<?php echo htmlspecialchars($publisher); ?>" required>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="Year">Year:</label>
                            <div class="controls">
                                <input type="text" id="Year" name="Year" value="<?php echo htmlspecialchars($year); ?>" required>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="Availability">Availability:</label>
                            <div class="controls">
                                <input type="text" id="Availability" name="Availability" value="<?php echo htmlspecialchars($avail); ?>" required>
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" name="submit" class="btn">Update Details</button>
                            </div>
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['submit'])) {
                        $name = $_POST['Title'];
                        $publisher = $_POST['Publisher'];
                        $year = $_POST['Year'];
                        $avail = $_POST['Availability'];

                        $sql1 = "UPDATE LMS.book SET Title='$name', Publisher='$publisher', Year='$year', Availability='$avail' WHERE BookId='$bookid'";
                        
                        if ($conn->query($sql1) === TRUE) {
                            $_SESSION['message'] = 'Book details updated successfully!';
                            $_SESSION['msg_type'] = 'success';
                            header("Location: edit_book_details.php?id=$bookid");
                            exit();
                        } else {
                            $_SESSION['message'] = 'Error: ' . $conn->error;
                            $_SESSION['msg_type'] = 'error';
                            header("Location: edit_book_details.php?id=$bookid");
                            exit();
                        }
                    }

                    // Display the message if it exists in the session
                    if (isset($_SESSION['message'])) {
                        echo '<div class="alert alert-' . ($_SESSION['msg_type'] == 'success' ? 'success' : 'danger') . '">' . $_SESSION['message'] . '</div>';
                        unset($_SESSION['message']);
                    }
                    ?>
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
