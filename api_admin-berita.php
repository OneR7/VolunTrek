<?php
require_once 'connection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Origin: *');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $judul = $_POST['judul'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $image = $_POST['image'] ?? '';

        if (empty($judul) || empty($deskripsi) || empty($image)) {
            http_response_code(400);
            echo json_encode(["message" => "All fields are required"]);
            exit;
        }

        $sql = "INSERT INTO berita (judul, deskripsi, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
            exit;
        }
        $stmt->bind_param("sss", $judul, $deskripsi, $image);

        if ($stmt->execute()) {
            $last_id = $stmt->insert_id;
            http_response_code(201);
            echo json_encode(["status" => "success", "message" => "Article created successfully", "id_berita" => $last_id]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Unable to create article", "error" => $stmt->error]);
        }

        $stmt->close();
        $conn->close();
        break;

    case 'GET':
        if (isset($_GET['id_berita'])) {
            $id_berita = intval($_GET['id_berita']);

            $sql = "SELECT * FROM berita WHERE id_berita = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
                exit;
            }
            $stmt->bind_param("i", $id_berita);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $article = [
                    'id_berita' => $row['id_berita'],
                    'judul' => $row['judul'],
                    'deskripsi' => $row['deskripsi'],
                    'image' => $row['image']
                ];
                echo json_encode($article);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Article not found"]);
            }

            $stmt->close();
            $conn->close();
        } else {
            $itemsPerPage = 9;
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $offset = ($page - 1) * $itemsPerPage;

            $sql = "SELECT * FROM berita ORDER BY id_berita DESC LIMIT ?, ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
                exit;
            }
            $stmt->bind_param("ii", $offset, $itemsPerPage);
            $stmt->execute();
            $result = $stmt->get_result();

            $articles = [];
            while ($row = $result->fetch_assoc()) {
                $articles[] = [
                    'id_berita' => $row['id_berita'],
                    'judul' => $row['judul'],
                    'deskripsi' => $row['deskripsi'],
                    'image' => $row['image']
                ];
            }

            echo json_encode($articles);
            $stmt->close();
            $conn->close();
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        $id_berita = $_GET['id_berita'] ?? '';
        $judul = $_PUT['judul'] ?? '';
        $deskripsi = $_PUT['deskripsi'] ?? '';
        $image = $_PUT['image'] ?? '';

        if (empty($id_berita) || empty($judul) || empty($deskripsi) || empty($image)) {
            http_response_code(400);
            echo json_encode(["message" => "All fields are required"]);
            exit;
        }

        $sql = "UPDATE berita SET judul = ?, deskripsi = ?, image = ? WHERE id_berita = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
            exit;
        }
        $stmt->bind_param("sssi", $judul, $deskripsi, $image, $id_berita);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["status" => "success", "message" => "Article updated successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Unable to update article", "error" => $stmt->error]);
        }

        $stmt->close();
        $conn->close();
        break;

    case 'DELETE':
        if (isset($_GET['id_berita'])) {
            $id_berita = intval($_GET['id_berita']);

            if (empty($id_berita)) {
                http_response_code(400);
                echo json_encode(["message" => "ID is required"]);
                exit;
            }

            $sql = "DELETE FROM berita WHERE id_berita = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
                exit;
            }
            $stmt->bind_param("i", $id_berita);

            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(["status" => "success", "message" => "Article deleted successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Unable to delete article", "error" => $stmt->error]);
            }

            $stmt->close();
            $conn->close();
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID is required"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        exit;
}
?>
