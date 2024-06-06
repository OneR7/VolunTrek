<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();

    if (isset($_SESSION['password_updated']) && $_SESSION['password_updated'] === true) {
        echo "<div class='alert alert-success notification-popup'>Password successfully updated. You can now log in with your new password.</div>";
    }
}

require_once "connection.php";

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $sandi = $_POST["password"];

    $sql_user = "SELECT * FROM user WHERE email = ?";
    $sql_admin = "SELECT * FROM admin WHERE email = ?";

    $stmt_user = mysqli_stmt_init($conn);
    $stmt_admin = mysqli_stmt_init($conn);

    // login buat yuser biasa
    if (mysqli_stmt_prepare($stmt_user, $sql_user)) {
        mysqli_stmt_bind_param($stmt_user, "s", $email);
        mysqli_stmt_execute($stmt_user);
        $result_user = mysqli_stmt_get_result($stmt_user);
        $user = mysqli_fetch_array($result_user, MYSQLI_ASSOC);

        if ($user) {
            if (password_verify($sandi, $user["password"])) {
                $_SESSION["user"] = $user["nama"];
                header("Location: index.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Password does not match</div>";
            }

        } else {
            echo "<div class='alert alert-danger'>Email not found</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error</div>";
    }

    // login buat atmin
    if (mysqli_stmt_prepare($stmt_admin, $sql_admin)) {
        mysqli_stmt_bind_param($stmt_admin, "s", $email);
        mysqli_stmt_execute($stmt_admin);
        $result_admin = mysqli_stmt_get_result($stmt_admin);
        $admin = mysqli_fetch_array($result_admin, MYSQLI_ASSOC);

        if ($admin && $email === "admin@gmail.com" && $sandi === "admin123") {
            header("Location: admin-index.php");
            exit();
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
    <title>VolunTrek - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">

    <style>
        .notification-popup {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            display: none;
            /* Initially hide the notification */
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email:" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password:" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="btn btn-primary">
            </div>
        </form>
        <div>
            <p>Not registered yet? <a href="registration.php">Register Here</a></p>
        </div>
        <div>
            <p>Forgot your password? <a href="forgot_password.php">Reset it here</a></p>
        </div>
    </div>
</body>

</html>