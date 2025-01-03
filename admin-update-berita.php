<?php
include "connection.php";

$id_berita = "";
$judul = "";
$deskripsi = "";
$image = "";

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["id"])) {
        header("location: admin-tampilan-berita.php");
        exit;
    }

    $id_berita = $_GET["id"];

    // SQL query to fetch the article by ID
    $sql = "SELECT * FROM berita WHERE id_berita=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_berita);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: admin-tampilan-berita.php");
        exit;
    }

    $judul = $row["judul"];
    $deskripsi = $row["deskripsi"];
    $image = $row["image"];

    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["id_berita"], $_POST["judulberita"], $_POST["deskripsiberita"], $_POST["linkgambar"])) {
        $id_berita = $_POST["id_berita"];
        $judul = $_POST["judulberita"];
        $deskripsi = $_POST["deskripsiberita"];
        $image = $_POST["linkgambar"];

        if (empty($id_berita) || empty($judul) || empty($deskripsi) || empty($image)) {
            $errorMessage = "Mohon isi semua kolom";
        } else {
            // Update data through API using POST method
            $api_url = "http://localhost/voluntrek_coba/api_admin-berita.php?id=" . $id_berita;
            $data = array(
                'judul' => $judul,
                'deskripsi' => $deskripsi,
                'image' => $image
            );

            $options = array(
                'http' => array(
                    'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                ),
            );

            $context  = stream_context_create($options);
            $result = file_get_contents($api_url, false, $context);

            if ($result === FALSE) {
                $errorMessage = "Gagal menghubungi API";
            } else {
                $response = json_decode($result, true);
                if (isset($response["status"]) && $response["status"] == "success") {
                    echo "<script>window.location.href='admin-tampilan-berita.php';</script>";
                    exit;
                } else {
                    $errorMessage = isset($response["message"]) ? $response["message"] : "Unknown error";
                }
            }
        }
    } else {
        $errorMessage = "Data tidak lengkap";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VolunTrek - Update Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">
</head>

<body>
<div class="container-fluid my-5">
    <h2>Update berita</h2>

    <?php
    if (!empty($errorMessage)) {
        echo "
        <div class='alert alert-warning alert-dismissible fade show' role='alert'>
            <strong>$errorMessage</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
    ";
    }
    ?>

    <form method="post">
        <input type="hidden" name="id_berita" value="<?php echo htmlspecialchars($id_berita); ?>">
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Judul berita</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="judulberita" value="<?php echo htmlspecialchars($judul); ?>">
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Deskripsi berita</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="deskripsiberita" value="<?php echo htmlspecialchars($deskripsi); ?>">
            </div>
        </div>
        <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Link gambar</label>
            <div class="col-sm-6">
                <input type="text" class="form-control" name="linkgambar" value="<?php echo htmlspecialchars($image); ?>">
            </div>
        </div>

        <div class="row mb-3">
            <div class="offset-sm-3 col-sm-3 d-grid">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="col-sm-3 d-grid">
                <a class="btn btn-outline-primary" href="admin-tampilan-berita.php" role="button">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>
</html>
