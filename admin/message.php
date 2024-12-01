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
        <title>LMS</title>
        <link rel="stylesheet" href="css/sidebar.css">
        <style>
           

            /* Message card styling */
            .message-card {
                background-color: white;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 400px;
                padding: 20px;
                border-radius: 5px;
                text-align: center;
            }

            .message-card h3 {
                margin-bottom: 20px;
            }

            .message-card input {
                width: calc(100% - 20px);
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 3px;
            }

            .message-card button {
                background-color: #007bff;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 3px;
                cursor: pointer;
            }

            .message-card button:hover {
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
            <!-- Message Card -->
            <div class="message-card">
                <h3>Send a Message</h3>

                <?php
                // Display notification message if set
                if (isset($_SESSION['notification'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['notification'] . '</div>';
                    unset($_SESSION['notification']); // Clear the notification after displaying
                }
                ?>

                <form action="message.php" method="post">
                    <input type="text" id="IDNo" name="IDNo" placeholder="Receiver ID No:" required>
                    <input type="text" id="Message" name="Message" placeholder="Enter Message" required>
                    <button type="submit" name="submit">Add Message</button>
                </form>

                <?php
                if (isset($_POST['submit'])) {
                    // Get the input values
                    $idno = $_POST['IDNo'];
                    $message = $_POST['Message'];

                    // Prepare an SQL statement to avoid SQL injection
                    $stmt = $conn->prepare("INSERT INTO LMS.message (IDNo, Msg, Date, Time) VALUES (?, ?, CURDATE(), CURTIME())");
                    $stmt->bind_param("ss", $idno, $message); // Bind parameters

                    if ($stmt->execute()) {
                        $_SESSION['notification'] = 'Message sent successfully!'; // Set notification message
                        header("Location: message.php"); // Redirect after success
                        exit();
                    } else {
                        $_SESSION['notification'] = 'Error sending message: ' . $stmt->error; // Set error message
                    }

                    $stmt->close(); // Close statement
                }
                ?>
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
