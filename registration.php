<?php
require_once "auth_registration.php"; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (isset($_POST["submit"])) {
    if (empty($_POST['nama'])) {
        $error = 'Please Enter Your Name';
    } else if (empty($_POST['email'])) {
        $error = 'Please Enter Your Email';
    } else if (empty($_POST['password'])) {
        $error = 'Please Enter Your Password';
    } else if (empty($_POST['repeat_password'])) {
        $error = 'Please Repeat Your Password';
    } else {
        // Check if email already exists
        $sql = "SELECT * FROM user WHERE email = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$_POST["email"]]);

        if ($statement->rowCount() > 0) {
            $error = 'Email Already Exists';
        } else {
            // Insert new user data
            $nama = trim($_POST['nama']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // SQL insert statement with correct placeholders
            $sqlInsert = "INSERT INTO user (nama, email, password, email_verification_status) VALUES (:nama, :email, :password, '0')";
            $statement = $conn->prepare($sqlInsert);
            $statement->bindParam(':nama', $nama);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':password', $password);

            if ($statement->execute()) {
                // Generate JWT token for verification link
                $key = '6a8f2e4c923e46ff1e8fa10d8a0f8b4d2f5c3e78e0b1a25f8e2d3f4c5a6b7c8d'; // Replace with your own secret key
                $payload = array('email' => $email);
                $token = JWT::encode($payload, $key, 'HS256');

                // Prepare verification link and send email
                $verificationLink = 'http://localhost/voluntrek_coba/verify.php?token=' . $token;

                // Send verification email
                sendVerificationEmail($email, $nama, $verificationLink);
            } else {
                $error = 'Registration failed. Please try again later.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./assets/images/favicon.png">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
          integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php if ($error !== ''): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($message !== ''): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <form id="registrationForm" method="post" action="">
        <div class="form-group">
            <input type="text" class="form-control" name="nama" placeholder="Full Name">
        </div>
        <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Email">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="submit">Register</button>
        </div>
    </form>

    <div>
        <p>Already Registered? <a href="login.php">Login Here</a></p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
