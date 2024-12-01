<?php
ob_start(); // Start output buffering

require('dbconn.php');

// Check if user is logged in
if (isset($_SESSION['IDNo'])) {
    // Check for session messages
    $message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
    unset($_SESSION['message']); // Clear message after displaying
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/sidebar.css">
        <title>Book Details - LMS</title>
        <style>
            /* Main content area */
            .content {
                flex-grow: 1;
                display: flex;
                justify-content: center; /* Center horizontally */
                align-items: center; /* Center vertically */
                background-color: #f0f0f0; /* Light background */
                padding: 20px;
                margin-left: 250px; /* Space for sidebar */
                overflow-y: auto; /* Allow scrolling if content exceeds */
            }

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

            .alert.alert-info {
                background-color: #17a2b8; /* Info color */
            }

            .book-info {
                font-size: 1.1em; /* Slightly larger font for book info */
                margin-bottom: 15px;
            }

            /* Sidebar styling */
            .sidebar {
                position: fixed; /* Fixed sidebar */
                width: 250px; /* Width of sidebar */
                height: 100%; /* Full height */
                background-color: #343a40; /* Dark background */
                color: white; /* Text color */
                padding: 20px; /* Padding for sidebar */
                display: flex;
                flex-direction: column;
            }

            /* Sidebar button styling */
            .sidebar button {
                background: none; /* No background */
                border: none; /* No border */
                color: white; /* Text color */
                font-size: 16px; /* Font size */
                text-align: left; /* Align text to the left */
                width: 100%; /* Full width for buttons */
                padding: 10px; /* Padding for buttons */
                cursor: pointer; /* Pointer cursor on hover */
                transition: background-color 0.3s; /* Smooth transition */
            }

            .sidebar button:hover {
                background-color: #495057; /* Highlight on hover */
            }

            .divider {
                height: 1px;
                background-color: #495057;
                margin: 10px 0; /* Space between buttons */
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
            <div class="module">
                <div class="module-head">
                    <h3>Book Details</h3>
                </div>
                <div class="module-body">
                    <?php if ($message): ?>
                        <div class="alert alert-info"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <?php
                    $x = $_GET['id'];
                    $sql = "SELECT * FROM LMS.book WHERE BookId='$x'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();

                    $bookid = $row['BookId'];
                    $name = $row['Title'];
                    $publisher = $row['Publisher'];
                    $year = $row['Year'];
                    $avail = $row['Availability'];

                    echo "<div class='book-info'><b><u>Book ID:</u></b> " . htmlspecialchars($bookid) . "</div>";
                    echo "<div class='book-info'><b><u>Title:</u></b> " . htmlspecialchars($name) . "</div>";
                    $sql1 = "SELECT * FROM LMS.author WHERE BookId='$bookid'";
                    $result = $conn->query($sql1);

                    echo "<div class='book-info'><b><u>Author:</u></b> ";
                    while ($row1 = $result->fetch_assoc()) {
                        echo htmlspecialchars($row1['Author']) . "&nbsp;";
                    }
                    echo "</div>";
                    echo "<div class='book-info'><b><u>Publisher:</u></b> " . htmlspecialchars($publisher) . "</div>";
                    echo "<div class='book-info'><b><u>Year:</u></b> " . htmlspecialchars($year) . "</div>";
                    echo "<div class='book-info'><b><u>Availability:</u></b> " . htmlspecialchars($avail) . "</div>";
                    ?>
                    <a href="book.php" class="btn">Go Back</a>
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
