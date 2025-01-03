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

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = cleanInput($_POST['email']);
    $password = cleanInput($_POST['password']);

    $sql_user = "SELECT * FROM user WHERE email = ?";
    $sql_admin = "SELECT * FROM admin WHERE email = ?";

    $stmt_user = $conn->prepare($sql_user);
    $stmt_admin = $conn->prepare($sql_admin);

    if ($stmt_user && $stmt_admin) {
        $stmt_user->bind_param("s", $email);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        $user = $result_user->fetch_assoc();

        if ($user) {
            if (password_verify($password, $user["password"])) {
                $_SESSION["user"] = $user["nama"];
                header("Location: index.php");
                exit;
            } else {
                $response["status"] = "error";
                $response["message"] = "Password salah!";
            }
        } else {
            $response["status"] = "error";
            $response["message"] = "Email tidak ditemukan!";
        }
        
        if ($response["status"] != "success") {
            $stmt_admin->bind_param("s", $email);
            $stmt_admin->execute();
            $result_admin = $stmt_admin->get_result();
            $admin = $result_admin->fetch_assoc();
        
            if ($admin && $email === "admin@gmail.com" && $password === "admin123") {
                $_SESSION["admin"] = $admin["nama"];
                header("Location: admin-index.php");
                exit;
            }
        }
    } else {
        $response["status"] = "error";
        $response["message"] = "Terjadi kesalahan pada server.";
    }

    echo json_encode($response);
}

$conn->close();
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
        <h1>Voluntrek</h1>
    </div>
    <div class="login__right flex">
        <div class="lr__header flex">
            <h1>Login</h1>
            <p>Toko bunga terbaik di Indonesia</p>
        </div>
        <div id="response" class="lr__error"></div>
        <form id="loginForm" method="POST">
            <div class="lr__input flex">
                <div class="input__box">
                    <i class="ri-mail-line"></i>
                    <input type="email" name="email" id="email" placeholder="Email" required class="box">
                </div>
                <div class="input__box">
                    <i class="ri-lock-2-line"></i>
                    <input type="password" name="password" id="password" placeholder="Password" required class="box">
                </div>
                <a href="forgot_password.php" class="forgot">Forgot Password?</a>
                <button class="log__in button" type="submit">Login</button>
                <div class="text__sign-up">Don't have an account? <a href="registerUser.php" class="reg__now">Register now</a></div>
            </div>
        </form>
    </div>
</div>

<script src="./assets/js/login.js"></script>
</body>
</html>
