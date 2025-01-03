<?php
session_start();
require_once "connection.php";

$response = [];

// Check if session is already set for user
if (isset($_SESSION["user"])) {
    $response["status"] = "redirect";
    $response["redirect"] = "index.php";
    echo json_encode($response);
    die();
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql_user = "SELECT * FROM user WHERE email = ?";
    $sql_admin = "SELECT * FROM admin WHERE email = ?";

    $stmt_user = mysqli_stmt_init($conn);
    $stmt_admin = mysqli_stmt_init($conn);

    // Login for regular user
    if (mysqli_stmt_prepare($stmt_user, $sql_user)) {
        mysqli_stmt_bind_param($stmt_user, "s", $email);
        mysqli_stmt_execute($stmt_user);
        $result_user = mysqli_stmt_get_result($stmt_user);
        $user = mysqli_fetch_array($result_user, MYSQLI_ASSOC);

        if ($user) {
            if (password_verify($password, $user["password"])) {
                $_SESSION["user"] = $user["nama"];
                $response["status"] = "success";
                $response["redirect"] = "index.php";
            } else {
                $response["status"] = "error";
                $response["message"] = "Password does not match";
            }
        } else {
            $response["status"] = "error";
            $response["message"] = "Email not found";
        }
    } else {
        $response["status"] = "error";
        $response["message"] = "Error";
    }

    // Login for admin
    if (mysqli_stmt_prepare($stmt_admin, $sql_admin)) {
        mysqli_stmt_bind_param($stmt_admin, "s", $email);
        mysqli_stmt_execute($stmt_admin);
        $result_admin = mysqli_stmt_get_result($stmt_admin);
        $admin = mysqli_fetch_array($result_admin, MYSQLI_ASSOC);

        if ($admin && $email === "admin@gmail.com" && $password === "admin123") {
            $_SESSION["admin"] = $admin["nama"];
            $response["status"] = "success";
            $response["redirect"] = "admin-index.php";
        }
    }

    echo json_encode($response);
    die();
}
?>
