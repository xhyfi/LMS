<?php
require('dbconn.php');

$message = ""; // Initialize message variable
$messageType = ""; // Initialize message type for CSS classes

// Check if the signup form is submitted
if (isset($_POST['signup'])) {
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $mobno = $_POST['PhoneNumber'];
    $idno = $_POST['IDNo'];
    $type = 'Student';

    // Check if the user already exists based on email or IDNo
    $check_sql = "SELECT * FROM LMS.user WHERE EmailId='$email' OR IDNo='$idno'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // User already exists
        $message = "Error: User with the same Email or ID number already exists!";
        $messageType = "error"; // Custom CSS class for error messages
    } else {
        // Proceed to insert the new user
        $sql = "INSERT INTO LMS.user (Name, Type, IDNo, EmailId, MobNo, Password) 
                VALUES ('$name', '$type', '$idno', '$email', '$mobno', '$password')";

        if ($conn->query($sql) === TRUE) {
            $message = "Registration Successful!";
            $messageType = "success"; // Custom CSS class for success messages
        } else {
            $message = "Error: Unable to register. Please try again.";
            $messageType = "error"; // Custom CSS class for error messages
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sidebar.css">  
    <title>Sign Up - LMS</title>
    <style>

        /* Main content area */
        .content {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center; /* Center the signup form */
            background-color: #f8f9fa; /* Light background for the main content */
            padding: 20px;
            margin-left: 250px; /* Space for sidebar */
            overflow-y: auto; /* Allow scrolling if content exceeds */
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
            margin-bottom: 20px; /* Increased spacing */
            text-align: left; /* Align labels to the left */
        }

        .form-group label {
            display: block; /* Make labels block-level */
            margin-bottom: 5px; /* Space between label and input */
            font-weight: bold; /* Bold labels */
            color: #495057; /* Darker color for labels */
        }

        .form-group input {
            width: 100%; /* Full width for inputs */
            padding: 10px; /* Padding for input fields */
            border: 1px solid #ced4da; /* Light gray border */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Light shadow for inputs */
            transition: border-color 0.3s; /* Smooth transition for focus */
        }

        .form-group input:focus {
            border-color: #007bff; /* Blue border on focus */
            outline: none; /* Remove default outline */
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
            transition: background-color 0.3s; /* Smooth hover transition */
            width: 100%; /* Full width for the button */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
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
            <h2>Sign Up</h2>

            <!-- Display message if set -->
            <?php if (!empty($message)): ?>
                <div class="message <?php echo htmlspecialchars($messageType); ?>" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group">
                    <label for="IDNo">ID Number:</label>
                    <input type="text" name="IDNo" id="IDNo" required>
                </div>
                <div class="form-group">
                    <label for="Name">Name:</label>
                    <input type="text" name="Name" id="Name" required>
                </div>
                <div class="form-group">
                    <label for="Email">Email:</label>
                    <input type="email" name="Email" id="Email" required>
                </div>
                <div class="form-group">
                    <label for="Password">Password:</label>
                    <input type="password" name="Password" id="Password" required>
                </div>
                <div class="form-group">
                    <label for="PhoneNumber">Mobile Number:</label>
                    <input type="text" name="PhoneNumber" id="PhoneNumber" required>
                </div>
                <button type="submit" name="signup" class="btn-primary">Sign Up</button>
            </form>
        </div>
    </div>

</body>
</html>
