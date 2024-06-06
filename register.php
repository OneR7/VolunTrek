<?php
session_start();
$message = isset($_GET['message']) ? urldecode($_GET['message']) : '';
if (isset($_SESSION["user"])) {
    header("Location: ind.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntrek Register</title>
    <link rel="stylesheet" href="sign_style.css">
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
            <h1>Register</h1>
            <p>Join us and make a positive impact on the world!</p>
        </div>
        <?php
        if (isset($_POST["submit"])) {
            function cleanInput($input) {
                $search = array(
                    '@<script[^>]*?>.*?</script>@si',
                    '@<[\/\!]*?[^<>]*?>@si',
                    '@<style[^>]*?>.*?</style>@siU',
                    '@<![\s\S]*?--[ \t\n\r]*>@'
                );
                $output = preg_replace($search, '', $input);
                return $output;
            }

            $fullName = cleanInput($_POST["nama"]);
            $email = cleanInput($_POST["email"]);
            $password = cleanInput($_POST["password"]);
            $passwordRepeat = cleanInput($_POST["repeat_password"]);

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();

            if (empty($fullName) or empty($email) or empty($password) or empty($passwordRepeat)) {
                array_push($errors, "All fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters long");
            }
            if ($password !== $passwordRepeat) {
                array_push($errors, "Password does not match");
            }

            require_once "connection.php";
            $sql = "SELECT * FROM user WHERE email = ?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $rowCount = mysqli_num_rows($result);

                if ($rowCount > 0) {
                    array_push($errors, "Email already exists!");
                }
            } else {
                array_push($errors, "Error preparing statement");
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='lr__error'><p>$error</p></div>";
                }
            } else {
                $sql = "INSERT INTO user (nama, email, password) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='lr__success'><p>You are registered successfully.</p></div>";
                } else {
                    die("Something went wrong");
                }
            }
        }
        ?>
        <form action="register.php" method="post">
            <div class="lr__input flex">
                <div class="input__box">
                    <i class="ri-user-line"></i>
                    <input type="text" name="nama" placeholder="Full Name" required class="box">
                </div>
                <div class="input__box">
                    <i class="ri-mail-line"></i>
                    <input type="email" name="email" placeholder="Email" required class="box">
                </div>
                <div class="input__box">
                    <i class="ri-lock-2-line"></i>
                    <input type="password" name="password" placeholder="Password" required class="box">
                </div>
                <div class="input__box">
                    <i class="ri-lock-2-line"></i>
                    <input type="password" name="repeat_password" placeholder="Repeat Password" required class="box">
                </div>
                <button class="log__in button" type="submit" name="submit">
                    Register
                </button>
                <div class="text__sign-up">Already Registered? <a href="loginUser.php" class="reg__now">Login here</a></div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
