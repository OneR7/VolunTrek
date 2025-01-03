<?php
require_once '../connection.php';

// Define the number of items per page
$itemsPerPage = isset($_GET['itemsPerPage']) ? intval($_GET['itemsPerPage']) : 12;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $itemsPerPage;

// Filter conditions
$filterConditions = array();
if (isset($_GET['fakultas']) && $_GET['fakultas'] != "") {
    $fakultas = mysqli_real_escape_string($conn, $_GET['fakultas']);
    $filterConditions[] = "fakultas.nama_fakultas = '$fakultas'";
}
$filterConditionsString = !empty($filterConditions) ? " AND " . implode(" AND ", $filterConditions) : "";

// Query to get volunteer data
$sql = "SELECT * FROM volunteer 
        INNER JOIN status ON volunteer.status_id_status = status.id_status
        INNER JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
        INNER JOIN tipe_kegiatan ON volunteer.tipe_kegiatan_tipe_kegiatan_id = tipe_kegiatan.id_tipe 
        WHERE 1 $filterConditionsString
        ORDER BY id_volunteer DESC
        LIMIT $offset, $itemsPerPage";

$result = $conn->query($sql);
$volunteers = array();
while ($row = $result->fetch_assoc()) {
    $volunteers[] = $row;
}

// Query to get total number of volunteers for pagination
$sqlCount = "SELECT COUNT(*) AS total FROM volunteer 
             INNER JOIN status ON volunteer.status_id_status = status.id_status
             INNER JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
             INNER JOIN tipe_kegiatan ON volunteer.tipe_kegiatan_tipe_kegiatan_id = tipe_kegiatan.id_tipe 
             WHERE 1 $filterConditionsString";
$resultCount = $conn->query($sqlCount);
$totalCount = $resultCount->fetch_assoc()['total'];

echo json_encode(array(
    'volunteers' => $volunteers,
    'total' => $totalCount,
    'itemsPerPage' => $itemsPerPage,
    'currentPage' => $page
));
