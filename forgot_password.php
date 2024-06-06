<!-- forgot_password_process.php -->
<?php
require_once "connection.php";

if (isset($_POST["reset_password"])) {
    $email = $_POST["email"];

    // Generate random password
    $new_password = bin2hex(random_bytes(8)); // Generates an 8-character random password

    // Update user password in the database
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql_update_password = "UPDATE user SET password = ? WHERE email = ?";
    $stmt_update_password = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt_update_password, $sql_update_password)) {
        mysqli_stmt_bind_param($stmt_update_password, "ss", $hashed_password, $email);
        mysqli_stmt_execute($stmt_update_password);
        // Add logic to send email notification here
        // ...

        echo "<div class='alert alert-success'>Password reset successful. Check your email for the new password.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating password.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VolunTrek - Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">
</head>

<body>
    <div class="container">
        <form action="forgot_password_process.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email:" name="email" class="form-control">
            </div>
            <form action="forgot_password_process.php" method="post">
                <div class="form-group">
                    <input type="password" placeholder="Enter New Password:" name="password" class="form-control">
                </div>
                <div class="form-btn">
                    <input type="submit" value="Reset Password" name="reset_password" class="btn btn-primary">
                </div>
            </form>
    </div>
</body>

</html>