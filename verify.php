<?php
require 'vendor/autoload.php';
require_once 'connection.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


$key = '6a8f2e4c923e46ff1e8fa10d8a0f8b4d2f5c3e78e0b1a25f8e2d3f4c5a6b7c8d';

$token = '';
$payload = array();

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
    echo "Connected successfully";

} catch(PDOException $e) {
    // Display error message if connection fails
    echo "Connection failed: " . $e->getMessage();
}

if(isset($_GET['token'])){
    $decoded = JWT::decode($_GET['token'], new Key($key, 'HS256'));
    $message = $decoded->msg;
}

if(isset($_GET['token'])){
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $decoded = JWT::decode($_GET['token'], new Key($key, 'HS256'));
    $checkQuery = 'SELECT email_verification_status FROM user WHERE email = "'.$decoded->email.'"';
    $result = $conn->query($checkQuery);
    foreach($result as $row){
        if($row['email_verification_status'] === 'Verified')
        {
            $payload = array(
                'msg' => 'Your Email Already Verified, Login Now!'
            );

        }
        else {
            $query = 'UPDATE user SET email_verification_status = "Verified" WHERE email = "'.$decoded->email.'"';
            $statement = $conn->prepare($query);
            $statement->execute();
            $payload = array(
                'msg' => 'Email Successfully Verified, Login Now!'
            );
        }
        $token = JWT::encode($payload, $key, 'HS256');
        header('location:login.php?token='.$token);
    }
}


?>