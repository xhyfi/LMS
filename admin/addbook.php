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
        <title>Add Book - LMS</title>
        <style>
            /* Main content area */
            .content {
                flex-grow: 1;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #f0f0f0;
                padding: 40px; /* Increase padding for more space */
                margin-left: 250px; /* Space for sidebar */
                overflow-y: auto; /* Allow scrolling if content exceeds */
            }

            /* Module styling */
            .module {
                background-color: white;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 40px; /* Increased padding for a spacious feel */
                width: 600px; /* Adjust width as necessary */
                border-radius: 8px;
                transition: transform 0.3s; /* Smooth hover effect */
            }

            .module:hover {
                transform: scale(1.02); /* Subtle zoom on hover */
            }

            .module-head h3 {
                margin: 0;
                padding-bottom: 20px;
                text-align: center;
                font-size: 1.8em; /* Larger font size for emphasis */
                color: #333;
            }

            /* Form styling */
            .control-group {
                margin-bottom: 25px; /* More margin for spacing */
            }

            .control-label {
                font-weight: bold;
                margin-bottom: 8px;
                color: #555;
            }

            .controls {
                display: flex;
                flex-direction: column;
            }

            input[type="text"],
            input[type="number"] {
                padding: 12px; /* Increased padding for comfort */
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 1em;
                transition: border-color 0.3s;
                margin-bottom: 2px; /* Added spacing below input fields */
            }

            input[type="text"]:focus,
            input[type="number"]:focus {
                border-color: #007bff;
                outline: none;
            }

            .btn {
                background-color: #007bff;
                color: white;
                border: none;
                padding: 14px 22px; /* Increased padding for buttons */
                border-radius: 4px;
                cursor: pointer;
                margin-top: 15px;
                font-size: 1.1em; /* Slightly larger button font */
                transition: background-color 0.3s, transform 0.2s;
            }

            .btn:hover {
                background-color: #0056b3;
                transform: translateY(-2px); /* Subtle lift effect on hover */
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
                    <h3>Add Book</h3>
                </div>
                <div class="module-body">
                    <?php if ($message): ?>
                        <div class="alert alert-info"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form class="form-horizontal row-fluid" action="addbook.php" method="post">
                        <div class="control-group">
                            <label class="control-label" for="Title"><b>Book Title</b></label>
                            <div class="controls">
                                <input type="text" id="title" name="title" placeholder="Enter Book Title" required>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="Author"><b>Author(s)</b></label>
                            <div class="controls">
                                <input type="text" id="author1" name="authors[]" placeholder="Author 1" required>
                                <input type="text" id="author2" name="authors[]" placeholder="Author 2" style="margin-top: 2px;">
                                <input type="text" id="author3" name="authors[]" placeholder="Author 3" style="margin-top: 2px;">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="Publisher"><b>Publisher</b></label>
                            <div class="controls">
                                <input type="text" id="publisher" name="publisher" placeholder="Enter Publisher" required>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="Year"><b>Year</b></label>
                            <div class="controls">
                                <input type="number" id="year" name="year" placeholder="Enter Year" required min="1900" max="<?php echo date('Y'); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="Availability"><b>Number of Copies</b></label>
                            <div class="controls">
                                <input type="number" id="availability" name="availability" placeholder="Enter Number of Copies" required min="1">
                            </div>
                        </div>

                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" name="submit" class="btn">Add Book</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </body>
    </html>

    <?php
    if (isset($_POST['submit'])) {
        // Collect data from the form
        $title = trim($_POST['title']);
        $authors = $_POST['authors']; // This will be an array
        $publisher = trim($_POST['publisher']);
        $year = intval(trim($_POST['year'])); // Convert to integer
        $availability = intval(trim($_POST['availability'])); // Convert to integer

        // Validate inputs
        if (empty($title) || empty($publisher) || empty($year) || $availability < 1 || empty($authors[0])) {
            $_SESSION['message'] = 'Please fill in all required fields.';
            header("Location: addbook.php");
            exit();
        }

        // Insert book into the database using a prepared statement
        $stmt = $conn->prepare("INSERT INTO LMS.book (Title, Publisher, Year, Availability) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $publisher, $year, $availability);

        if ($stmt->execute()) {
            $bookId = $stmt->insert_id; // Get the ID of the newly inserted book

            // Insert authors into the database using prepared statements
            $authorStmt = $conn->prepare("INSERT INTO LMS.author (BookId, Author) VALUES (?, ?)");
            foreach ($authors as $author) {
                $author = trim($author);
                if (!empty($author)) {
                    $authorStmt->bind_param("is", $bookId, $author);
                    $authorStmt->execute();
                }
            }
            $_SESSION['message'] = 'Book added successfully!';
        } else {
            $_SESSION['message'] = 'Error adding book. Please try again.';
        }

        // Close statements
        $stmt->close();
        $authorStmt->close();
        header("Location: addbook.php");
        exit();
    }

    // Close database connection
    $conn->close();
    ob_end_flush(); // Flush the output buffer
} else {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}
?>
