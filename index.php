<?php
require('dbconn.php');

// Handle login when form is submitted
if (isset($_POST['signin'])) {
    $u = $_POST['IDNo'];
    $p = $_POST['Password'];

    // Query the database to check the credentials
    $sql = "SELECT * FROM LMS.user WHERE IDNo = '$u'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $x = $row['Password'];
        $y = $row['Type'];

        // Check if the password matches (case-insensitive)
        if (strcasecmp($x, $p) == 0 && !empty($u) && !empty($p)) {
            // Set session and redirect based on user type
            session_start(); // Make sure session is started
            $_SESSION['IDNo'] = $u;

            if ($y == 'Admin') {
                header('Location: admin/index.php');
                exit(); // Stop further script execution
            } else {
                header('Location: student/index.php');
                exit(); // Stop further script execution
            }
        } else {
            // If password doesn't match, set a login error message
            $error_message = 'Failed to Login! Incorrect IDNo or Password.';
        }
    } else {
        // If user doesn't exist, set a login error message
        $error_message = 'Failed to Login! Incorrect IDNo or Password.';
    }
}
?>

<!DOCTYPE html>
<html>

<!-- Head -->
<head>
    <title>Library Management System</title>

    <!-- Meta-Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- //Meta-Tags -->

    <!-- Style -->
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all">

    <!-- Fonts -->
    <link href="//fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <!-- //Fonts -->
</head>
<!-- //Head -->

<!-- Body -->
<body>

    <h1>LIBRARY MANAGEMENT SYSTEM</h1>

    <div class="container">

        <div class="login">
            <h2>Sign In</h2>
            <!-- Login form -->
            <form action="index.php" method="post">
                <input type="text" name="IDNo" placeholder="ID Number" required="">
                <input type="password" name="Password" placeholder="Password" required="">
            
                <div class="send-button">
                    <input type="submit" name="signin" value="Sign In">
                </div>
            </form>

            <!-- Display error message if login failed -->
            <?php if (isset($error_message)): ?>
                <div class="error-message" style="color:red; margin-top: 10px;">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="clear"></div>

    </div>

</body>
<!-- //Body -->

</html>
