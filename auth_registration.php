<?php
require_once "connection.php"; // Include your database connection file
require_once "vendor/autoload.php"; // Include Composer's autoload.php for PHPMailer

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$error = '';
$message = '';

$servername = "localhost";
$username = "root";
$password = ""; // Leave empty if no password is set
$dbname = "voluntrek";

try {
    // Establish PDO database connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Display message upon successful connection (optional)
    // echo "Connected successfully";

} catch(PDOException $e) {
    // Display error message if connection fails
    echo "Connection failed: " . $e->getMessage();
}

if(isset($_GET['token'])){
    $decoded = JWT::decode($_GET['token'], new Key($key, 'HS256'));
    $message = $decoded->msg;
}

function sendVerificationEmail($email, $nama, $verificationLink) {
    global $error, $message;

    // Send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'restantimayzaluna@gmail.com'; // Your Gmail email address
        $mail->Password = 'yddv wwyh uihk cjxy'; // Your Gmail password or App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // TCP port to connect to

        // Sender and recipient settings
        $mail->setFrom('restantimayzaluna@gmail.com', 'VolunTrek');
        $mail->addAddress($email, $nama);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email Address';
        $mail->Body = '<p>Hello ' . $nama . ',<br>To complete your registration and activate your account, please click on the following link:<br><a href="' . $verificationLink . '">' . $verificationLink . '</a></p>';

        $mail->send();
        $message = 'Verification email has been sent. Please check your email.';
    } catch (Exception $e) {
        $error = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}
?>
