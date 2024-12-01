<?php
require('dbconn.php');

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $Description = $_POST['Description'];
    $idno = $_SESSION['IDNo'];

    $sql1 = "INSERT INTO LMS.recommendations (Book_Name, Description, IDNo) VALUES ('$title', '$Description', '$idno')"; 

    if ($conn->query($sql1) === TRUE) {
        $_SESSION['message'] = 'Success';
        $_SESSION['msg_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error: ' . $conn->error;
        $_SESSION['msg_type'] = 'danger';
    }

    // Redirect to avoid form resubmission
    header("Location: recommendations.php");
    exit();
}

if (!isset($_SESSION['IDNo'])) {
    $_SESSION['message'] = 'Access Denied!!!';
    $_SESSION['msg_type'] = 'danger';
    header("Location: current.php", true, 303);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - Recommend a Book</title>
    <link rel="stylesheet" href="css/sidebar.css">
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
            overflow-y: auto;
        }

        /* Form card styling */
        .form-container {
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            padding: 30px;
            margin-top: 20px;
            border-radius: 10px;
        }

        .form-container h3 {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #0056b3;
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
            margin: 10px 0;
        }

        /* Alert styling */
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: white;
        }

        .alert-success {
            background-color: #28a745;
        }

        .alert-danger {
            background-color: #dc3545;
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
        <!-- Form Card -->
        <div class="form-container">
            <h3 class="text-center">Recommend a Book</h3>

            <!-- Display success or error message -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['msg_type'] === 'success' ? 'success' : 'danger'; ?>">
                    <?php 
                    echo htmlspecialchars($_SESSION['message']); 
                    unset($_SESSION['message']); // Clear the message after displaying
                    ?>
                </div>
            <?php endif; ?>

            <form method="post" action="recommendations.php">
                <div class="form-group">
                    <label for="Title"><b>Book Title</b></label>
                    <input type="text" id="title" name="title" placeholder="Enter Book Title" required>
                </div>

                <div class="form-group">
                    <label for="Description"><b>Description</b></label>
                    <input type="text" id="Description" name="Description" placeholder="Enter Book Description" required>
                </div>

                <div class="form-group">
                    <button type="submit" name="submit">Submit Recommendation</button>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>
