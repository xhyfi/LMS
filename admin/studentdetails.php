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
        <title>Student Details - LMS</title>
        <style>
            /* Main content area */
            .content {
                flex-grow: 1;
                display: flex;
                justify-content: center; /* Center horizontally */
                align-items: center; /* Center vertically */
                background-color: #f0f0f0; /* Light background */
                padding: 40px;
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

            .student-info {
                font-size: 1.1em; /* Slightly larger font for student info */
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
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5); /* Sidebar shadow */
            }

            .sidebar h1 {
                font-size: 24px; /* Font size for title */
                margin-bottom: 20px; /* Space below title */
            }

            .sidebar button {
                width: 100%; /* Full width for buttons */
                background: none; /* No background */
                border: none; /* No border */
                color: white; /* Text color */
                text-align: left; /* Align text to the left */
                padding: 10px; /* Padding for buttons */
                cursor: pointer; /* Pointer cursor on hover */
                transition: background-color 0.3s; /* Smooth transition */
            }

            .sidebar button:hover {
                background-color: rgba(255, 255, 255, 0.1); /* Highlight on hover */
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
                    <h3>Student Details</h3>
                </div>
                <div class="module-body">
                    <?php if ($message): ?>
                        <div class="alert alert-info"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <?php
                    $rno = $_GET['id'];
                    $sql = "SELECT * FROM LMS.user WHERE IDNo='$rno'";
                    $result = $conn->query($sql);

                    if ($result && $row = $result->fetch_assoc()) {
                        $name = $row['Name'];
                        $email = $row['EmailId'];
                        $mobno = $row['MobNo'];

                        echo "<div class='student-info'><b><u>Name:</u></b> " . htmlspecialchars($name) . "</div>";
                        echo "<div class='student-info'><b><u>ID No:</u></b> " . htmlspecialchars($rno) . "</div>";
                        echo "<div class='student-info'><b><u>Email Id:</u></b> " . htmlspecialchars($email) . "</div>";
                        echo "<div class='student-info'><b><u>Mobile No:</u></b> " . htmlspecialchars($mobno) . "</div>";
                    } else {
                        echo "<b>Student details not found.</b>";
                    }
                    ?>
                    <a href="student.php" class="btn">Go Back</a>
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
