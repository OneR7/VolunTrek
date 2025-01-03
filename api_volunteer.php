<?php
require_once 'connection.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Origin: *');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    // case 'POST':
    //     $data = json_decode(file_get_contents('php://input'), true);

    //     $required_fields = [
    //         'nama_kegiatan', 'nama_penyelenggara', 'image', 'tanggal_kegiatan', 'link_pendaftaran',
    //         'deskripsi', 'keterangan', 'status_id_status', 'fakultas_id_fakultas',
    //         'tipe_kegiatan_tipe_kegiatan_id', 'cp', 'nama_status', 'nama_fakultas', 'nama_tipe'
    //     ];

    //     foreach ($required_fields as $field) {
    //         if (empty($data[$field])) {
    //             http_response_code(400);
    //             echo json_encode(["message" => "All fields are required"]);
    //             exit;
    //         }
    //     }

    //     $sql = "INSERT INTO volunteer (nama_kegiatan, nama_penyelenggara, image, tanggal_kegiatan, link_pendaftaran, deskripsi, keterangan, status_id_status, fakultas_id_fakultas, tipe_kegiatan_tipe_kegiatan_id, cp, nama_status, nama_fakultas, nama_tipe) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    //     $stmt = $conn->prepare($sql);
    //     if (!$stmt) {
    //         http_response_code(500);
    //         echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    //         exit;
    //     }
    //     $stmt->bind_param("sssssssiiisss", 
    //         $data['nama_kegiatan'], $data['nama_penyelenggara'], $data['image'], $data['tanggal_kegiatan'],
    //         $data['link_pendaftaran'], $data['deskripsi'], $data['keterangan'], $data['status_id_status'], 
    //         $data['fakultas_id_fakultas'], $data['tipe_kegiatan_tipe_kegiatan_id'], $data['cp'], 
    //         $data['nama_status'], $data['nama_fakultas'], $data['nama_tipe']
    //     );

    //     if ($stmt->execute()) {
    //         http_response_code(201);
    //         echo json_encode(["status" => "success", "message" => "Volunteer created successfully"]);
    //     } else {
    //         http_response_code(500);
    //         echo json_encode(["status" => "error", "message" => "Unable to create volunteer", "error" => $stmt->error]);
    //     }

    //     $stmt->close();
    //     $conn->close();
    //     break;

    case 'GET':
        if (isset($_GET['id_volunteer'])) {
            $id_volunteer = intval($_GET['id_volunteer']);
            
            $sql = "SELECT volunteer.*, status.nama_status, fakultas.nama_fakultas, tipe_kegiatan.nama_tipe 
                    FROM volunteer 
                    INNER JOIN status ON volunteer.status_id_status = status.id_status
                    INNER JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
                    INNER JOIN tipe_kegiatan ON volunteer.tipe_kegiatan_tipe_kegiatan_id = tipe_kegiatan.id_tipe 
                    WHERE volunteer.id_volunteer = ?";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
                exit;
            }
            $stmt->bind_param("i", $id_volunteer);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                echo json_encode($row);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Volunteer not found"]);
            }
            
            $stmt->close();
            $conn->close();
            exit;
        }

        // Define the number of items per page
        $itemsPerPage = isset($_GET['itemsPerPage']) ? intval($_GET['itemsPerPage']) : 12;

        // Get the current page number from the query parameters
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

        // Calculate the offset for the SQL query
        $offset = ($page - 1) * $itemsPerPage;

        // Initialize the filter condition
        $filterConditions = "";

        // Check if a fakultas filter is set
        if (isset($_GET['fakultas']) && $_GET['fakultas'] != "Pilih fakultas...") {
            $fakultas = mysqli_real_escape_string($conn, $_GET['fakultas']);
            $filterConditions = "AND fakultas.nama_fakultas = '$fakultas'";
        }

        // SQL query to fetch volunteer data with pagination and filtering
        $sql = "SELECT volunteer.*, status.nama_status, fakultas.nama_fakultas, tipe_kegiatan.nama_tipe 
                FROM volunteer 
                INNER JOIN status ON volunteer.status_id_status = status.id_status
                INNER JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
                INNER JOIN tipe_kegiatan ON volunteer.tipe_kegiatan_tipe_kegiatan_id = tipe_kegiatan.id_tipe 
                WHERE 1=1 $filterConditions
                ORDER BY id_volunteer DESC
                LIMIT ?, ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
            exit;
        }
        $stmt->bind_param("ii", $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();

        $volunteers = [];
        while ($row = $result->fetch_assoc()) {
            $volunteers[] = $row;
        }

        // Get the total number of volunteers to calculate total pages
        $sqlCount = "SELECT COUNT(*) AS total FROM volunteer 
                     INNER JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
                     WHERE 1=1 $filterConditions";
        $totalResult = $conn->query($sqlCount);
        $totalRow = $totalResult->fetch_assoc();
        $totalVolunteers = $totalRow['total'];
        $totalPages = ceil($totalVolunteers / $itemsPerPage);

        $response = [
            'page' => $page,
            'total_pages' => $totalPages,
            'volunteers' => $volunteers
        ];

        echo json_encode($response);

        $stmt->close();
        $conn->close();
        break;

        case 'POST':
            $nama_kegiatan = $_POST['nama_kegiatan'] ?? '';
            $nama_penyelenggara = $_POST['nama_penyelenggara'] ?? '';
            $image = $_POST['image'] ?? '';
            $tanggal_kegiatan = $_POST['tanggal_kegiatan'] ?? '';
            $link_pendaftaran = $_POST['link_pendaftaran'] ?? '';
            $deskripsi = $_POST['deskripsi'] ?? '';
            $keterangan = $_POST['keterangan'] ?? '';
            $status_id_status = $_POST['status_id_status'] ?? '';
            $fakultas_id_fakultas = $_POST['fakultas_id_fakultas'] ?? '';
            $tipe_kegiatan_tipe_kegiatan_id = $_POST['tipe_kegiatan_tipe_kegiatan_id'] ?? '';
            $cp = $_POST['cp'] ?? '';
        
            if (empty($nama_kegiatan) || empty($nama_penyelenggara) || empty($image) || empty($tanggal_kegiatan) || empty($link_pendaftaran) || empty($deskripsi) || empty($keterangan) || empty($status_id_status) || empty($fakultas_id_fakultas) || empty($tipe_kegiatan_tipe_kegiatan_id) || empty($cp)) {
                http_response_code(400);
                echo json_encode(["message" => "All fields are required"]);
                exit;
            }
        
            $sql = "INSERT INTO volunteer (nama_kegiatan, nama_penyelenggara, image, tanggal_kegiatan, link_pendaftaran, deskripsi, keterangan, status_id_status, fakultas_id_fakultas, tipe_kegiatan_tipe_kegiatan_id, cp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
                exit;
            }
            $stmt->bind_param("sssssssiiss", 
                $nama_kegiatan, $nama_penyelenggara, $image, $tanggal_kegiatan, $link_pendaftaran, $deskripsi, $keterangan, $status_id_status, $fakultas_id_fakultas, $tipe_kegiatan_tipe_kegiatan_id, $cp
            );
        
            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(["status" => "success", "message" => "Volunteer created successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Unable to create volunteer", "error" => $stmt->error]);
            }
        
            $stmt->close();
            $conn->close();
            break;
        

        case 'PUT':
            // Parse the raw PUT data
            parse_str(file_get_contents("php://input"), $_PUT);
        
            // Define the required fields for updating
            $required_fields = [
                'id_volunteer', 'nama_kegiatan', 'nama_penyelenggara', 'image', 'tanggal_kegiatan',
                'link_pendaftaran', 'deskripsi', 'keterangan', 'status_id_status', 'fakultas_id_fakultas',
                'tipe_kegiatan_tipe_kegiatan_id', 'cp'
            ];
        
            // Check if all required fields are provided
            foreach ($required_fields as $field) {
                if (empty($_PUT[$field])) {
                    http_response_code(400);
                    echo json_encode(["message" => "All fields are required"]);
                    exit;
                }
            }
        
            // Prepare the SQL statement for updating volunteer data
            $sql = "UPDATE volunteer SET nama_kegiatan = ?, nama_penyelenggara = ?, image = ?, tanggal_kegiatan = ?, link_pendaftaran = ?, deskripsi = ?, keterangan = ?, status_id_status = ?, fakultas_id_fakultas = ?, tipe_kegiatan_tipe_kegiatan_id = ?, cp = ? WHERE id_volunteer = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
                exit;
            }
        
            // Bind parameters and execute the update query
            $stmt->bind_param("sssssssiisii",
                $_PUT['nama_kegiatan'], $_PUT['nama_penyelenggara'], $_PUT['image'], $_PUT['tanggal_kegiatan'],
                $_PUT['link_pendaftaran'], $_PUT['deskripsi'], $_PUT['keterangan'], $_PUT['status_id_status'],
                $_PUT['fakultas_id_fakultas'], $_PUT['tipe_kegiatan_tipe_kegiatan_id'], $_PUT['cp'], $_PUT['id_volunteer']
            );
        
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(["status" => "success", "message" => "Volunteer updated successfully"]);
            } else {
                http_response_code(500);
                echo json_encode(["status" => "error", "message" => "Unable to update volunteer", "error" => $stmt->error]);
            }
        
            // Close the statement and database connection
            $stmt->close();
            $conn->close();
            break;
        

    case 'DELETE':
        if (!isset($_GET['id_volunteer'])) {
            http_response_code(400);
            echo json_encode(["message" => "ID is required"]);
            exit;
        }

        $id_volunteer = intval($_GET['id_volunteer']);

        $sql = "DELETE FROM volunteer WHERE id_volunteer = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
            exit;
        }
        $stmt->bind_param("i", $id_volunteer);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                http_response_code(200);
                echo json_encode(["status" => "success", "message" => "Volunteer deleted successfully"]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Volunteer not found"]);
            }
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Unable to delete volunteer", "error" => $stmt->error]);
        }

        $stmt->close();
        $conn->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        exit;
}
?>
