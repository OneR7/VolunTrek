<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Volunteer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">
</head>

<?php include "admin-header.php"; ?>

<body style="margin-top: 9%;">
    <div class="container-fluid my-5">
        <h2 class="h2 section-subtitle">Database Volunteer</h2>

        <p class="section-text" style="font-size: var(--fs-6)">
            Admin bisa menambahkan dan mengedit data kegiatan volunteer.
        </p>

        <div action="" class="title-wrapper" style="text-align: center;">
            <button type="#" class="btn btn-primary" onclick="window.location.href='admin-add-volunteer.php'">Add
                Volunteer</button>
        </div>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kegiatan</th>
                    <th>Nama Penyelenggara</th>
                    <th>Link Gambar</th>
                    <th>Tanggal Kegiatan</th>
                    <th>Link Pendaftaran</th>
                    <th>Deskripsi</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Fakultas</th>
                    <th>Tipe Kegiatan</th>
                    <th>CP</th>
                </tr>
            </thead>
            <tbody>
                <?php

                include "connection.php";
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT *
                FROM volunteer
                JOIN status ON volunteer.status_id_status = status.id_status
                JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
                JOIN tipe_kegiatan ON volunteer.tipe_kegiatan_tipe_kegiatan_id = tipe_kegiatan.id_tipe
                ORDER BY id_volunteer DESC";
                $result = $conn->query($sql);

                if (!$result) {
                    die("Invalid query: " . $conn->error);
                }

                // read data
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[id_volunteer]</td>
                        <td>$row[nama_kegiatan]</td>
                        <td>$row[nama_penyelenggara]</td>
                        <td>$row[image]</td>
                        <td>$row[tanggal_kegiatan]</td>
                        <td>$row[link_pendaftaran]</td>
                        <td>$row[deskripsi]</td>
                        <td>$row[keterangan]</td>
                        <td>$row[nama_status]</td>
                        <td>$row[nama_fakultas]</td>
                        <td>$row[nama_tipe]</td>
                        <td>$row[cp]</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='admin-update-volunteer.php?id=$row[id_volunteer]'>Update</a>
                        </td>
                    </tr>
                    ";
                }
                ?>


            </tbody>
        </table>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

</body>

</html>