<?php
session_start();

// Include file koneksi.php
require_once './connection.php';

// Fungsi untuk membersihkan input dari karakter khusus
function cleanInput($input)
{
    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Menghapus tag <script>
        '@<[\/\!]*?[^<>]*?>@si',            // Menghapus tag HTML
        '@<style[^>]*?>.*?</style>@siU',    // Menghapus tag <style>
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Menghapus komentar multi-baris
    );

    $output = preg_replace($search, '', $input);
    return $output;
}

// Memeriksa apakah form login telah disubmit
if (isset($_POST['submit'])) {
    // Mendapatkan email dan password dari form login
    $email = cleanInput($_POST['email']);
    $password = cleanInput($_POST['password']);

    // Login untuk user biasa
    $sql_user = "SELECT * FROM user WHERE email = ?";
    $stmt_user = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt_user, $sql_user)) {
        mysqli_stmt_bind_param($stmt_user, "s", $email);
        mysqli_stmt_execute($stmt_user);
        $result_user = mysqli_stmt_get_result($stmt_user);
        $user = mysqli_fetch_array($result_user, MYSQLI_ASSOC);

        if ($user) {
            if (password_verify($password, $user["password"])) {
                $_SESSION["user"] = $user["nama"];
                header("Location: index.php");
                exit();
            } else {
                $error = "Password does not match";
            }
        } else {
            $error = "Email not found";
        }
    } else {
        $error = "Error preparing statement for user";
    }

    // Login untuk admin
    $sql_admin = "SELECT * FROM admin WHERE email = ?";
    $stmt_admin = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt_admin, $sql_admin)) {
        mysqli_stmt_bind_param($stmt_admin, "s", $email);
        mysqli_stmt_execute($stmt_admin);
        $result_admin = mysqli_stmt_get_result($stmt_admin);
        $admin = mysqli_fetch_array($result_admin, MYSQLI_ASSOC);

        if ($admin) {
            if ($email === "admin@gmail.com" && $password === "admin123") {
                $_SESSION["admin"] = $admin["nama"];
                header("Location: admin-index.php");
                exit();
            } else {
                $error = "Incorrect admin credentials";
            }
        } else {
            $error = "Admin email not found";
        }
    } else {
        $error = "Error preparing statement for admin";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntrek Login</title>
    <!-- CSS-->
    <link rel="stylesheet" href="sign_style.css">
    <!-- REMIX ICON -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
<div class="login__container container">
    <div class="login__left flex">
        <div class="background-image"></div>
        <!-- <h1>Voluntrek</h1> -->
    </div>
    <div class="login__right flex">
        <div class="lr__header flex">
            <h1>Login</h1>
            <p>Join us and make a positive impact on the world!</p>
        </div>
        <?php if (isset($error)) { ?>
            <div class="lr__error">
                <p><?php echo $error; ?></p>
            </div>
        <?php } ?>
        <form action="" method="POST">
            <div class="lr__input flex">
                <div class="input__box">
                    <i class="ri-mail-line"></i>
                    <input type="email" name="email" placeholder="Email" required class="box">
                </div>
                <div class="input__box">
                    <i class="ri-lock-2-line"></i>
                    <input type="password" name="password" placeholder="Password" required class="box">
                </div>
                <!-- <a href="#" class="forgot">Forgot Password? </a> -->
                <button class="log__in button" type="submit" name="submit">
                    Login
                </button>
                <div class="text__sign-up">Don't have an account? <a href="registerUser.php" class="reg__now">register now</a></div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
