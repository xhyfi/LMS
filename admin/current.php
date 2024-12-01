<?php
require('dbconn.php');
?>

<?php 
if (isset($_SESSION['IDNo'])) {
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS</title>
    <link rel="stylesheet" href="css/sidebar.css">
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

        /* Form styling */
        .form-horizontal {
            margin-bottom: 20px;
            display: flex;
            justify-content: center; /* Center the form elements */
            gap: 10px; /* Space between form elements */
        }

        .form-horizontal input {
            flex: 1; /* Allow the input to grow */
            max-width: 300px; /* Limit input width */
            padding: 10px; /* Add padding to input */
            border: 1px solid #ccc; /* Border color */
            border-radius: 5px; /* Rounded corners */
            font-size: 14px; /* Font size for input */
        }

        .form-horizontal button {
            min-width: 80px; /* Minimum width for button */
            padding: 10px; /* Add padding to button */
            background-color: #007bff; /* Primary button color */
            color: white; /* Text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer on hover */
            transition: background-color 0.3s; /* Transition effect */
        }

        .form-horizontal button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }

        /* Divider styling */
        
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
            <!-- Search Form -->
            <form class="form-horizontal" action="current.php" method="post">
                <input type="text" id="title" name="title" placeholder="Search Roll No/Book Name/Book ID" required>
                <button type="submit" name="submit">Search</button>
            </form>

            <div class="divider"></div> <!-- Divider below the search -->

            <?php
            if (isset($_POST['submit'])) {
                $s = $_POST['title'];
                $sql = "SELECT record.BookId, IDNo, Title, Due_Date, Date_of_Issue, DATEDIFF(CURDATE(), Due_Date) AS x 
                        FROM LMS.record, LMS.book 
                        WHERE (Date_of_Issue IS NOT NULL AND Date_of_Return IS NULL AND book.Bookid = record.BookId) 
                        AND (IDNo='$s' OR record.BookId='$s' OR Title LIKE '%$s%')";
            } else {
                $sql = "SELECT record.BookId, IDNo, Title, Due_Date, Date_of_Issue, DATEDIFF(CURDATE(), Due_Date) AS x 
                        FROM LMS.record, LMS.book 
                        WHERE Date_of_Issue IS NOT NULL AND Date_of_Return IS NULL 
                        AND book.Bookid = record.BookId";
            }
            $result = $conn->query($sql);
            $rowcount = mysqli_num_rows($result);

            if ($rowcount == 0) {
                echo "<br><center><h2><b><i>No Results</i></b></h2></center>";
            } else {
                ?>
                <table class="table" id="tables">
                    <thead>
                        <tr>
                            <th>Roll No</th>
                            <th>Book ID</th>
                            <th>Book Name</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Dues</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            $idno = $row['IDNo'];
                            $bookid = $row['BookId'];
                            $name = $row['Title'];
                            $issuedate = $row['Date_of_Issue'];
                            $duedate = $row['Due_Date'];
                            $dues = $row['x'];
                        ?>
                        <tr>
                            <td><?php echo strtoupper($idno); ?></td>
                            <td><?php echo $bookid; ?></td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $issuedate; ?></td>
                            <td><?php echo $duedate; ?></td>
                            <td><?php if ($dues > 0) echo "<font color='red'>" . $dues . "</font>"; else echo "<font color='green'>0</font>"; ?></td>
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
    echo "<div class='alert alert-danger'>Access Denied!!! You need to log in to access this page.</div>";
}
?>
