<?php
session_start(); // Start the session at the beginning of the script
require_once "connection.php"; // Include your database connection file
require_once "vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = '6a8f2e4c923e46ff1e8fa10d8a0f8b4d2f5c3e78e0b1a25f8e2d3f4c5a6b7c8d';

$servername = "localhost";
$username = "root";
$password = ""; // Leave empty if no password is set
$dbname = "voluntrek";

$response = array('status' => '', 'message' => '');

try {
    // Establish PDO database connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    // Display error message if connection fails
    $response['status'] = 'error';
    $response['message'] = "Connection failed: " . $e->getMessage();
    echo json_encode($response);
    exit();
}

if (isset($_POST["login"])) {
    if (empty($_POST["email"])) {
        $response['status'] = 'error';
        $response['message'] = 'Please Enter Email';
        echo json_encode($response);
        exit();
    } elseif (empty($_POST["password"])) {
        $response['status'] = 'error';
        $response['message'] = 'Please Enter Password';
        echo json_encode($response);
        exit();
    } else {
        // Check if the email and password match the hardcoded admin credentials
        if ($_POST["email"] === 'admin@gmail.com' && $_POST["password"] === 'admin123') {
            $_SESSION['user'] = $_POST["email"]; // Set the session with the admin's email
            $_SESSION['username'] = 'Admin'; // Set the session with the admin's name
            $response['status'] = 'success';
            $response['message'] = 'Login successfully as admin';
            if (isRequestFromPostman()) {
                echo json_encode($response);
            } else {
                header('Location: admin-index.php');
                exit();
            }
        } else {
            // Proceed with database check for other users
            $sql = "SELECT * FROM user WHERE email = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$_POST["email"]]);
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $data = $statement->fetch();

            if ($data) {
                if ($data['password'] === $_POST["password"]) {
                    $_SESSION['user'] = $_POST["email"];
                    $_SESSION['username'] = $data['nama'];

                    $token = JWT::encode(
                        [
                            'iat' => time(),
                            'nbf' => time(),
                            'exp' => time() + 3600,
                            'data' => [
                                'id_admin' => $data['id_admin'],
                                'nama' => $data['nama']
                            ]
                        ],
                        $key,
                        'HS256'
                    );
                    setcookie("token", $token, time() + 3600, "/", "", true, true);
                    $response['status'] = 'success';
                    $response['message'] = 'Login successfully';
                    $response['token'] = $token;

                    if (isRequestFromPostman()) {
                        echo json_encode($response);
                    } else {
                        header('Location: index.php');
                        exit();
                    }
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Password Wrong';
                    echo json_encode($response);
                    exit();
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Email Wrong';
                echo json_encode($response);
                exit();
            }
        }
    }
}

function isRequestFromPostman() {
    return strpos($_SERVER['HTTP_USER_AGENT'], 'PostmanRuntime') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Postman') !== false;
}
?>
