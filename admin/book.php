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
                flex-grow: 1;
                display: flex;
                justify-content: center;
                align-items: flex-start; /* Align items to the start */
                background-color: #f8f9fa; /* Light background for the main content */
                padding: 20px;
                margin-left: 250px; /* Space for sidebar */
                overflow-y: auto; /* Allow scrolling if content exceeds */
            }

            /* Table container styling */
            .table-container {
                width: 100%; /* Full width for the table container */
                max-width: 800px; /* Prevent overflow */
                border-radius: 10px;
                background-color: white;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                padding: 20px; /* Padding around the table */
                overflow-x: auto; /* Allow horizontal scrolling if necessary */
            }

            /* Search form styling */
            .search-form {
                display: flex; /* Use flexbox for centering */
                flex-direction: column; /* Stack elements vertically */
                align-items: center; /* Center elements */
                width: 100%; /* Full width */
                padding: 20px 0; /* Padding around the search form */
            }

            .search-form .control-group {
                display: flex; /* Use flexbox for alignment */
                justify-content: center; /* Center items */
                gap: 10px; /* Space between input and button */
                width: 100%; /* Full width for input and button */
            }

            .search-form input[type="text"] {
                flex: 1; /* Allow input to grow */
                padding: 10px; /* Padding for input field */
                border: 1px solid #ccc; /* Border for input */
                border-radius: 3px; /* Rounded corners */
            }

            .edit-button {
                background-color: #28a745; /* Green for Edit button */
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 3px;
                cursor: pointer;
                text-decoration: none; /* Remove underline for links */
                white-space: nowrap; /* Prevent text wrapping */
            }

            .edit-button:hover {
                background-color: #218838; /* Darker green on hover */
            }

            .details-button {
                background-color: #007bff; /* Blue for Details button */
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 3px;
                cursor: pointer;
                text-decoration: none; /* Remove underline for links */
                white-space: nowrap; /* Prevent text wrapping */
            }

            .details-button:hover {
                background-color: #0056b3; /* Darker blue on hover */
            }

            /* Table styling */
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
                <form class="form-horizontal search-form" action="book.php" method="post">
                    <div class="control-group">
                        <label class="control-label" for="Search"><b>Search:</b></label>
                        <input type="text" id="title" name="title" placeholder="Enter Name/ID of Book" class="span8" required>
                        <button type="submit" name="submit" class="edit-button">Search</button>
                    </div>
                </form>

                <br>
                <?php
                if (isset($_POST['submit'])) {
                    $s = $_POST['title'];
                    $sql = "SELECT * FROM LMS.book WHERE BookId='$s' OR Title LIKE '%$s%'";
                } else {
                    $sql = "SELECT * FROM LMS.book";
                }

                $result = $conn->query($sql);
                $rowcount = mysqli_num_rows($result);

                if (!$rowcount) {
                    echo "<br><center><h2><b><i>No Results</i></b></h2></center>";
                } else {
                    ?>
                    <table class="table" id="tables2">
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
                                    <td><b><?php echo htmlspecialchars($avail); ?></b></td>
                                    <td>
                                        <center>
                                            <a href="bookdetails.php?id=<?php echo $bookid; ?>" class="details-button">Details</a>
                                            <a href="edit_book_details.php?id=<?php echo $bookid; ?>" class="edit-button">Edit</a>
                                        </center>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div style="padding-bottom: 20px;"></div> <!-- Padding at the bottom of the table -->
                <?php } ?>
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
