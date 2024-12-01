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
                align-items: flex-start; /* Align items to the start */
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

            /* Table styling */
            .table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px; /* Space above the table */
            }

            .table th, .table td {
                padding: 10px;
                text-align: left;
                border: 1px solid #dee2e6;
            }

            .table th {
                background-color: #007bff;
                color: white;
            }

            .table tr:nth-child(even) {
                background-color: #f8f9fa;
            }

            /* Card styling for messages */
            .card {
                background-color: white;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 100%; /* Full width */
                padding: 20px;
                text-align: left;
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
            <div class="card">
                <h3>Your Messages</h3>
                <table class="table" id="tables">
                    <thead>
                        <tr>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $idno = $_SESSION['IDNo'];
                        $sql = "SELECT * FROM LMS.message WHERE IDNo='$idno' ORDER BY Date DESC, Time DESC";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            $msg = htmlspecialchars($row['Msg']); // Escape output to prevent XSS
                            $date = htmlspecialchars($row['Date']);
                            $time = htmlspecialchars($row['Time']);
                        ?>
                            <tr>
                                <td><?php echo $msg; ?></td>
                                <td><?php echo $date; ?></td>
                                <td><?php echo $time; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
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
