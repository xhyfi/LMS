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
        <title>LMS</title>
        <style>
            /* Main content area */
            .content {
                flex-grow: 1;
                display: flex;
                justify-content: center;
                align-items: center; /* Center the profile card */
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

            /* Cards styling */
            .card {
                background-color: white;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 300px;
                padding: 20px;
                text-align: center;
            }

            .card img {
                width: 100px;
                height: 100px;
                border-radius: 50%;
                margin-bottom: 20px;
            }

            .edit-button {
                background-color: #007bff;
                color: white;
                border: none;
                padding: 10px 20px;
                margin-top: 20px;
                border-radius: 3px;
                cursor: pointer;
            }

            .edit-button:hover {
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
            <!-- Profile Card -->
            <div class="card">
                <?php
                $idno = $_SESSION['IDNo'];
                $sql = "SELECT * FROM LMS.user WHERE IDNo='$idno'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                $name = $row['Name'];
                $email = $row['EmailId'];
                $mobno = $row['MobNo'];
                ?>
                <img src="images/profile2.png" alt="Profile Picture">
                <h3><?php echo htmlspecialchars($name); ?></h3>
                <p><b>ID No: </b><?php echo htmlspecialchars($idno); ?></p> <!-- Added ID Number -->
                <p><b>Email ID: </b><?php echo htmlspecialchars($email); ?></p>
                <p><b>Mobile number: </b><?php echo htmlspecialchars($mobno); ?></p>
                <button class="edit-button" onclick="location.href='edit_student_details.php'">Edit Details</button>
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
