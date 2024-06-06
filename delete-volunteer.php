<?php
require_once 'connection.php';

if (isset($_GET['id'])) {
    $volunteerId = $_GET['id'];

    // Gunakan prepared statement untuk mencegah SQL injection
    $deleteSql = "DELETE FROM volunteer WHERE id_volunteer = ?";

    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param('i', $volunteerId);

    // Eksekusi prepared statement
    if ($stmt->execute()) {
        echo "Volunteer berhasil dihapus";
    } else {
        echo "Error deleting volunteer: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>