<?php
session_start();
require_once "connection.php";

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST["nama"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $errors = [];

    if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat)) {
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

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            array_push($errors, "Email already exists");
        }
    } else {
        array_push($errors, "Database error");
    }

    if (empty($errors)) {
        $sql = "INSERT INTO user (nama, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
            mysqli_stmt_execute($stmt);
            $response["status"] = "success";
            $response["message"] = "Registration successful";
        } else {
            $response["status"] = "error";
            $response["message"] = "Database error";
        }
    } else {
        $response["status"] = "error";
        $response["message"] = $errors;
    }
} else {
    $response["status"] = "error";
    $response["message"] = "Invalid request";
}

echo json_encode($response);
?>
