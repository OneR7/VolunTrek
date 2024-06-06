<!-- forgot_password_process.php -->
<?php
require_once "connection.php";

if (isset($_POST["reset_password"])) {
    $email = $_POST["email"];
    $new_password = $_POST["password"]; // Password from the form

    // Validate the new password
    if (empty($new_password)) {
        echo "<div class='alert alert-danger'>New password is required.</div>";
        exit();
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update user password in the database
    $sql_update_password = "UPDATE user SET password = ? WHERE email = ?";
    $stmt_update_password = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt_update_password, $sql_update_password)) {
        mysqli_stmt_bind_param($stmt_update_password, "ss", $hashed_password, $email);
        mysqli_stmt_execute($stmt_update_password);
        // Add logic to send email notification here
        // ...

        echo "<div class='alert alert-success'>Password reset successful.</div>";
        header("Location: login.php");
    } else {
        echo "<div class='alert alert-danger'>Error updating password.</div>";
    }
}
?>