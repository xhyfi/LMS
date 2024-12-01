<?php
require('dbconn.php');

// Check if user is logged in
if (!isset($_SESSION['IDNo'])) {
    // Redirect to an error page or display an error message
    header("Location: error.php?message=Access Denied");
    exit();
}
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
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            background-color: #f8f9fa; /* Light background for the main content */
            padding: 20px;
            margin-left: 250px; /* Space for sidebar */
            overflow-y: auto; /* Allow scrolling if content exceeds */
            height: calc(100vh - 40px); /* Full height minus some padding */
        }

        /* Card styling */
        .card {
            background-color: white; /* Card background */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Shadow effect */
            margin: 20px; /* Space around cards */
            padding: 20px; /* Padding inside cards */
            text-align: center; /* Center text */
            transition: transform 0.3s; /* Smooth hover effect */
            width: 200px; /* Fixed width for cards */
        }

        .card:hover {
            transform: scale(1.05); /* Scale up on hover */
        }

        .card img {
            width: 50px; /* Fixed width for icons */
            height: 50px; /* Fixed height for icons */
            margin-bottom: 10px; /* Space below icons */
        }

        .card h3 {
            margin: 10px 0; /* Space above and below titles */
            font-size: 18px; /* Title font size */
        }

        .card p {
            color: #555; /* Text color for description */
            font-size: 14px; /* Description font size */
        }

        .card button {
            margin-top: 10px; /* Space above buttons */
            padding: 10px 15px; /* Padding for buttons */
            background-color: #007bff; /* Bootstrap primary color */
            color: white; /* Text color */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor */
            transition: background-color 0.3s; /* Smooth transition */
            display: block; /* Block display to center */
            margin-left: auto; /* Center horizontally */
            margin-right: auto; /* Center horizontally */
        }

        .card button:hover {
            background-color: #0056b3; /* Darker shade on hover */
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
        <div class="card">
            <img src="images/book2.png" alt="Issue Requests">
            <h3>Issue Requests</h3>
            <p>Manage and view current issue requests from students.</p>
            <button onclick="location.href='issue_requests.php'">View Requests</button>
        </div>
        <div class="card">
            <img src="images/book3.png" alt="Renew Request">
            <h3>Renew Request</h3>
            <p>Process renewal requests for issued books.</p>
            <button onclick="location.href='renew_requests.php'">View Renewals</button>
        </div>
        <div class="card">
            <img src="images/book4.png" alt="Return Requests">
            <h3>Return Requests</h3>
            <p>Handle return requests and update records accordingly.</p>
            <button onclick="location.href='return_requests.php'">View Returns</button>
        </div>
    </div>
</body>

</html>
