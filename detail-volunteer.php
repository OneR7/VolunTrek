<?php
require_once 'connection.php';

$volunteerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch berita details from the database
$sql2 = "SELECT * FROM volunteer 
        INNER JOIN status ON volunteer.status_id_status = status.id_status
        INNER JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
        INNER JOIN tipe_kegiatan ON volunteer.tipe_kegiatan_tipe_kegiatan_id = tipe_kegiatan.id_tipe
        WHERE id_volunteer = $volunteerId ";
$result = $conn->query($sql2);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nama_kegiatan = $row['nama_kegiatan'];
    $nama_penyelenggara = $row['nama_penyelenggara'];
    $image = $row['image'];
    $tanggal_kegiatan = $row['tanggal_kegiatan'];
    $link_pendaftaran = $row['link_pendaftaran'];
    $deskripsi = $row['deskripsi'];
    $keterangan = $row['keterangan'];
    $nama_status = $row['nama_status'];
    $nama_fakultas = $row['nama_fakultas'];
    $nama_tipe = $row['nama_tipe'];
    $cp = $row['cp'];
} else {
    // Redirect to an error page or handle as needed
    header("Location: error.php");
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VolunTrek - </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/detail-volunteer.css">
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container-fluid text-center" style="margin-top: 10%; margin-bottom: 10%">
        <div class="row align-items-start">
            <div class="col-7">
                <div class="card mb-3 border-0">
                    <img src="<?php echo $image; ?>" class="card-img-top" alt="<?php echo $nama_kegiatan; ?>">
                    <div class="card-body d-flex justify-content-between">
                        <div class="d-flex">
                            <ion-icon name="person"></ion-icon>
                            <p>
                                <?php echo $nama_kegiatan; ?>
                            </p>
                        </div>
                    </div>
                    <p class="card-text">
                        <?php echo $deskripsi; ?>
                    </p>
                    <h6>Keterangan</h6>
                    <p class="metode">
                        <?php echo $keterangan; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telegram/Whatsapp,
                        dan online meeting (Ms Team/gmeet/zoom)
                    </p>
                </div>
            </div>
            <div class="col-4 kanan border">
                <div class="col">
                    <h5>Rekrutmen Relawan <br>
                        <?php echo $nama_kegiatan; ?>
                    </h5>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-outline-danger border-2 mt-3" disabled>
                        <?php echo $nama_tipe; ?>
                    </button>
                </div>
                <div class="col mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-calendar3" viewBox="0 0 16 16">
                        <path
                            d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z" />
                        <path
                            d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2m3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                    </svg>
                    &nbsp;&nbsp;
                    <?php echo $nama_status; ?>
                </div>
                <div class="col mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-stopwatch-fill" viewBox="0 0 16 16">
                        <path
                            d="M6.5 0a.5.5 0 0 0 0 1H7v1.07A7.001 7.001 0 0 0 8 16a7 7 0 0 0 5.29-11.584.531.531 0 0 0 .013-.012l.354-.354.353.354a.5.5 0 1 0 .707-.707l-1.414-1.415a.5.5 0 1 0-.707.707l.354.354-.354.354a.717.717 0 0 0-.012.012A6.973 6.973 0 0 0 9 2.071V1h.5a.5.5 0 0 0 0-1zm2 5.6V9a.5.5 0 0 1-.5.5H4.5a.5.5 0 0 1 0-1h3V5.6a.5.5 0 1 1 1 0" />
                    </svg>
                    &nbsp;&nbsp;
                    <?php echo $tanggal_kegiatan; ?> <br>
                </div>
                <div class="col mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6" />
                    </svg>
                    &nbsp;&nbsp;
                    <?php echo $nama_fakultas; ?>
                </div>
                <div class="col mt-3 logoseru">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-exclamation-triangle-fill mt-2" viewBox="0 0 16 16">
                        <path
                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                    </svg>
                    &nbsp;&nbsp;
                    <?php echo $cp; ?>
                </div>
                <div class="col mt-3">
                    <a href="<?php echo $link_pendaftaran; ?>" type="button" class="btn btn-warning"><svg
                            xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-person-add" viewBox="0 0 18 18">
                            <path
                                d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                            <path
                                d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1z" />
                        </svg>JADI RELAWAN</a>
                </div>

            </div>
        </div>
    </div>
    </main>
    </article>

    <?php include "footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>