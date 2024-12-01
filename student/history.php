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
        <title>LMS - Previously Borrowed Books</title>
        <style>
            /* Main content area */
            .content {
                flex-grow: 1;
                display: flex;
                justify-content: center;
                align-items: flex-start; /* Align to the top */
                background-color: #f0f0f0;
                padding: 20px;
                margin-left: 250px; /* Space for sidebar */
                overflow-y: auto; /* Allow scrolling if content exceeds */
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
                background-color: white;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 600px;
                padding: 20px;
                margin: 0 auto; /* Center card */
                text-align: center;
                border-radius: 5px;
            }

            /* Table styling */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th, td {
                padding: 10px;
                text-align: left;
                border: 1px solid #ddd;
            }

            th {
                background-color: #343a40;
                color: white;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            .search-bar {
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
            }

            .search-bar input {
                padding: 10px;
                width: 80%;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            .search-bar button {
                padding: 10px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 4px;
                margin-left: 10px;
                cursor: pointer;
            }

            .search-bar button:hover {
                background-color: #0056b3;
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
            <button onclick="location.href='logout.php'" style="margin-top: auto;">Logout</button> <!-- Push logout to the bottom -->
        </div>

        <!-- Main Content Area -->
        <div class="content">
            <!-- Card for search and results -->
            <div class="card">
                <!-- Search Form -->
                <form class="search-bar" action="history.php" method="post">
                    <input type="text" id="title" name="title" placeholder="Enter Book Name/Book ID" required>
                    <button type="submit" name="submit">Search</button>
                </form>

                <?php
                $idno = $_SESSION['IDNo'];
                if (isset($_POST['submit'])) {
                    $s = $_POST['title'];
                    $sql = "SELECT * FROM LMS.record, LMS.book WHERE IDNo = '$idno' AND Date_of_Issue IS NOT NULL AND Date_of_Return IS NOT NULL AND book.Bookid = record.BookId AND (record.BookId='$s' OR Title LIKE '%$s%')";
                } else {
                    $sql = "SELECT * FROM LMS.record, LMS.book WHERE IDNo = '$idno' AND Date_of_Issue IS NOT NULL AND Date_of_Return IS NOT NULL AND book.Bookid = record.BookId";
                }

                $result = $conn->query($sql);
                $rowcount = mysqli_num_rows($result);

                if ($rowcount == 0) {
                    echo "<br><h2><b><i>No Results</i></b></h2>";
                } else {
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Book ID</th>
                                <th>Book Name</th>
                                <th>Issue Date</th>
                                <th>Return Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $result->fetch_assoc()) {
                                $bookid = htmlspecialchars($row['BookId']);
                                $name = htmlspecialchars($row['Title']);
                                $issuedate = htmlspecialchars($row['Date_of_Issue']);
                                $returndate = htmlspecialchars($row['Date_of_Return']);
                                ?>
                                <tr>
                                    <td><?php echo $bookid; ?></td>
                                    <td><?php echo $name; ?></td>
                                    <td><?php echo $issuedate; ?></td>
                                    <td><?php echo $returndate; ?></td>
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
    // Redirect to an error page or display an error message
    header("Location: error.php?message=Access Denied");
    exit();
}
?>
