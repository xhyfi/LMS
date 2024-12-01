<?php
ob_start();

require('dbconn.php');

// Initialize message variable and type
$message = "";
$messageType = "";

// Check if user is logged in
if (isset($_SESSION['IDNo'])) {
    $idno = $_SESSION['IDNo'];

    // Fetch user details from the database
    $sql = "SELECT * FROM LMS.user WHERE IDNo='$idno'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $name = $row['Name'];
    $email = $row['EmailId'];
    $mobno = $row['MobNo'];
    $pswd = $row['Password'];

    // Check if form is submitted
    if (isset($_POST['submit'])) {
        // Get the posted values
        $name = $_POST['Name'];
        $email = $_POST['EmailId'];
        $mobno = $_POST['MobNo'];
        $pswd = $_POST['Password'];

        // Update the user details
        $sql1 = "UPDATE LMS.user SET Name='$name', EmailId='$email', MobNo='$mobno', Password='$pswd' WHERE IDNo='$idno'";

        if ($conn->query($sql1) === TRUE) {
            // Set success message
            $message = "Details updated successfully!";
            $messageType = "success"; // Custom CSS class for success messages
        } else {
            // Set error message
            $message = "Error updating details: " . $conn->error;
            $messageType = "error"; // Custom CSS class for error messages
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sidebar.css">  
    <title>Update Details - LMS</title>
    <style>
        /* Main content area */
        .content {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            padding: 20px;
            margin-left: 250px; /* Space for sidebar */
            overflow-y: auto;
        }

        /* Form container styling */
        .form-container {
            width: 400px; /* Fixed width for the form */
            padding: 30px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #495057;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .btn-primary:hover {
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
        <div class="form-container">
            <h2>Update Details</h2>

            <!-- Display message if set -->
            <?php if (!empty($message)): ?>
                <div class="message <?php echo htmlspecialchars($messageType); ?>" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="edit_admin_details.php?id=<?php echo $idno ?>" method="post">
                <div class="form-group">
                    <label for="Name">Name:</label>
                    <input type="text" id="Name" name="Name" value="<?php echo htmlspecialchars($name); ?>" required>
                </div>
                <div class="form-group">
                    <label for="EmailId">Email:</label>
                    <input type="email" id="EmailId" name="EmailId" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="form-group">
                    <label for="MobNo">Mobile Number:</label>
                    <input type="text" id="MobNo" name="MobNo" value="<?php echo htmlspecialchars($mobno); ?>" required>
                </div>
                <div class="form-group">
                    <label for="Password">New Password:</label>
                    <input type="password" id="Password" name="Password" value="<?php echo htmlspecialchars($pswd); ?>" required>
                </div>
                <button type="submit" name="submit" class="btn-primary">Update Details</button>
            </form>
        </div>
    </div>

</body>
</html>

<?php
} else {
    // Redirect to an error page
    header("Location: error.php?message=Access Denied");
    exit();
}
?>
