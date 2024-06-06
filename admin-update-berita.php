<?php
include "connection.php";

$id_berita = "";
$judul = "";
$deskripsi = "";
$image = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["id"])) {
        header("location: admin-tampilan-berita.php");
        exit;
    }

    $id_berita = $_GET["id"];

    $sql = "SELECT * FROM berita WHERE id_berita=$id_berita";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: admin-tampilan-berita.php");
        exit;
    }

    $judul = $row["judul"];
    $deskripsi = $row["deskripsi"];
    $image = $row["image"];
} else {
    $id_berita = $_POST["id_berita"];
    $judul = $_POST["judulberita"];
    $deskripsi = $_POST["deskripsiberita"];
    $image = $_POST["linkgambar"];

    do {
        if (empty($id_berita) || empty($judul) || empty($deskripsi) || empty($image)) {
            $errorMessage = "Mohon isi semua kolom";
            break;
        }

        $sql = "UPDATE berita SET judul = '$judul', deskripsi = '$deskripsi', image = '$image' WHERE id_berita = $id_berita";
        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $conn->error;
            break;
        }

        $successMessage = "Berita berhasil diupdate";

        header("location: admin-tampilan-berita.php");
        exit;

    } while (true);
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
            <input type="hidden" name="id_berita" value="<?php echo $id_berita; ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Judul berita</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="judulberita" value="<?php echo $judul; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Deskripsi berita</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="deskripsiberita" value="<?php echo $deskripsi; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Link gambar</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="linkgambar" value="<?php echo $image; ?>">
                </div>
            </div>

            <?php
            if (!empty($successMessage)) {
                echo "
                <div class='row mb-3'>
                    <div class='offset-sm-3 col-sm-6'>
                        <div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>$successMessage</strong>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                    </div>
                </div>
                ";
            }
            ?>

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