<?php
session_start(); // Start the session at the beginning of the script
require_once "connection.php"; // Include your database connection file
require_once "vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$message = '';
$error = '';

if (isset($_POST["login"])) {
    include "auth_login.php";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VolunTrek - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
          integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .notification-popup {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            padding: 10px;
            color: #fff;
            display: none;
        }
    </style>
</head>
<body>
<?php
if ($error !== '') {
    echo '<div class="alert alert-danger">' . $error . '</div>';
}

if ($message !== '') {
    echo '<div class="alert alert-info">' . $message . '</div>';
}
?>
<div class="container">
    <form id="loginForm" method="post">
        <div class="form-group">
            <input type="email" placeholder="Enter Email:" name="email" class="form-control" id="email">
        </div>
        <div class="form-group">
            <input type="password" placeholder="Enter Password:" name="password" class="form-control" id="password">
        </div>
        <div class="form-btn">
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </div>
    </form>
    <div>
        <p>Not registered yet? <a href="registration.php">Register Here</a></p>
    </div>
    <div>
        <p>Forgot your password? <a href="forgot_password.php">Reset it here</a></p>
    </div>
    <div id="response" class="notification-popup"></div>
</div>
</body>
</html>
