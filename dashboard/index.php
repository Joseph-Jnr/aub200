<?php
session_start();

if (!isset($_SESSION['app_user'])) {
	header("Location: ../index.php?unauthorized_access");
	exit();
}

$username = $_SESSION["app_user"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container-fluid d-flex align-items-center">
        <div class="message">
            <div class="header">
                <img src="../assets/img/pngfind.com-bell-icon-png-50085.png" alt="christmas icon">
            </div>
            <div class="body">
                <h2>Welcome <?php echo $username; ?>!</h2>
                <p>Your login was successful. <b><marquee behavior="" direction="">Merry Christmas and a happy new year!</marquee></b></p>
            </div>
        </div>
    </div>
</body>
</html>