<?php
require_once 'connection.php';

if (isset($_GET['id'])) {
    $beritaId = $_GET['id'];

    // Gunakan prepared statement untuk mencegah SQL injection
    $deleteSql = "DELETE FROM berita WHERE id_berita = ?";

    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param('i', $beritaId);

    // Eksekusi prepared statement
    if ($stmt->execute()) {
        echo "berita berhasil dihapus";
    } else {
        echo "Error deleting berita: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>