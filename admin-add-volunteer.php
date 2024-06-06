<?php
require_once 'connection.php';

$nama_kegiatan = "";
$nama_penyelenggara = "";
$image = "";
$tanggal_kegiatan = "";
$link_pendaftaran = "";
$deskripsi = "";
$keterangan = "";
$cp = "";

$errorMessage = "";
$successMessage = "";

if (isset($_POST['proses'])) {
    $id_status = $_POST['id_status'];
    $id_fakultas = $_POST['id_fakultas'];
    $id_tipe_kegiatan = $_POST['id_tipe_kegiatan'];

    $nama_kegiatan = $_POST['nama_kegiatan'];
    $nama_penyelenggara = $_POST['nama_penyelenggara'];
    $image = $_POST['image'];
    $tanggal_kegiatan = $_POST['tanggal_kegiatan'];
    $link_pendaftaran = $_POST['link_pendaftaran'];
    $deskripsi = $_POST['deskripsi'];
    $keterangan = $_POST['keterangan'];
    $cp = $_POST['cp'];

    // Validate form fields (you can customize this based on your requirements)
    if (empty($nama_kegiatan) || empty($nama_penyelenggara) || empty($tanggal_kegiatan) || empty($link_pendaftaran) || empty($deskripsi) || empty($keterangan) || empty($cp)) {
        $errorMessage = "Semua kolom harus diisi";
    } else {
        $sqlInsert = "INSERT INTO volunteer (nama_kegiatan, nama_penyelenggara, image, tanggal_kegiatan, link_pendaftaran, deskripsi, keterangan, status_id_status, fakultas_id_fakultas, tipe_kegiatan_tipe_kegiatan_id, cp)
                      VALUES ('$nama_kegiatan', '$nama_penyelenggara', '$image', '$tanggal_kegiatan', '$link_pendaftaran', '$deskripsi', '$keterangan', '$id_status', '$id_fakultas', '$id_tipe_kegiatan', '$cp')";

        $resultInsert = $conn->query($sqlInsert);

        if (!$resultInsert) {
            $errorMessage = "Invalid query: " . $conn->error;
        } else {
            // Data successfully inserted, reset form values
            $nama_kegiatan = "";
            $nama_penyelenggara = "";
            $image = "";
            $tanggal_kegiatan = "";
            $link_pendaftaran = "";
            $deskripsi = "";
            $keterangan = "";
            $nama_status = "";
            $nama_fakultas = "";
            $nama_tipe = "";
            $cp = "";

            $successMessage = "Kegiatan volunteer berhasil ditambahkan";
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Volunteer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">
</head>

<body>

    <div class="container-fluid my-5">
        <h2>Tambah kegiatan volunteer</h2>

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
            <div class="row mb-3">
                <label class="col-sm-3 col-form-tabel">Nama Kegiatan</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="nama_kegiatan" value="<?php echo $nama_kegiatan; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-tabel">Nama Penyelenggara</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="nama_penyelenggara"
                        value="<?php echo $nama_penyelenggara; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-tabel">Link Gambar</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="image" value="<?php echo $image; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-tabel">Tanggal Kegiatan</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="tanggal_kegiatan"
                        value="<?php echo $tanggal_kegiatan; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-tabel">Link Pendaftaran</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="link_pendaftaran"
                        value="<?php echo $link_pendaftaran; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-tabel">Deskripsi</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="deskripsi" value="<?php echo $deskripsi; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-tabel">Keterangan</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="keterangan" value="<?php echo $keterangan; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-tabel">Status</label>
                <div class="col-sm-6">
                    <select class="form-select" name="id_status" aria-label="Default select example">
                        <?php
                        $queryStatus = mysqli_query($conn, "SELECT * FROM status") or die(mysqli_error($conn));
                        while ($dataStatus = mysqli_fetch_array($queryStatus)) {
                            $selected = ($dataStatus['id_status'] == $nama_status) ? "selected" : "";
                            echo "<option value=$dataStatus[id_status] $selected>$dataStatus[nama_status]</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-tabel">Fakultas</label>
                <div class="col-sm-6">
                    <select class="form-select" name="id_fakultas" aria-label="Default select example">
                        <?php
                        $queryFakultas = mysqli_query($conn, "SELECT * FROM fakultas") or die(mysqli_error($conn));
                        while ($dataFakultas = mysqli_fetch_array($queryFakultas)) {
                            $selected = ($dataFakultas['id_fakultas'] == $nama_fakultas) ? "selected" : "";
                            echo "<option value=$dataFakultas[id_fakultas] $selected>$dataFakultas[nama_fakultas]</option>";
                        }
                        ?>
                    </select>

                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-tabel">Tipe Kegiatan</label>
                <div class="col-sm-6">
                    <select class="form-select" name="id_tipe_kegiatan" aria-label="Default select example">
                        <?php
                        $queryTipeKegiatan = mysqli_query($conn, "SELECT id_tipe, nama_tipe FROM tipe_kegiatan") or die(mysqli_error($conn));
                        while ($dataTipeKegiatan = mysqli_fetch_array($queryTipeKegiatan)) {
                            $selected = ($dataTipeKegiatan['id_tipe'] == $nama_tipe) ? "selected" : "";
                            echo "<option value=$dataTipeKegiatan[id_tipe] $selected>$dataTipeKegiatan[nama_tipe]</option>";
                        }
                        ?>
                    </select>

                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-tabel">CP</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="cp" value="<?php echo $cp; ?>">
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
                    <button type="submit" class="btn btn-primary" name="proses">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="admin-tampilan-volunteer.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>