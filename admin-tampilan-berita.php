<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VolunTrek - Berita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">

    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container-fluid {
            text-align: center;
        }

        .title-wrapper {
            margin-top: 20px;
        }
    </style>
</head>

<?php include "admin-header.php"; ?>

<body style="margin-top: 7%;">
    <div class="container-fluid my-5">
        <h2 class="h2 section-subtitle">Database Berita</h2>

        <p class="section-text" style="font-size: var(--fs-6)">
            Admin bisa menambahkan dan mengedit data berita volunteer.
        </p>

        <div action="" class="title-wrapper">
            <button type="#" class="btn btn-primary" onclick="window.location.href='admin-add-berita.php'">Add
                Berita</button>
        </div>

        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul berita</th>
                    <th>Deskripsi berita</th>
                    <th>Link Gambar</th>
                </tr>
            </thead>
            <tbody>
                <?php

                include "connection.php";
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM berita
                		ORDER BY id_berita DESC";
                $result = $conn->query($sql);

                if (!$result) {
                    die("Invalid query: " . $conn->error);
                }

                // read data
                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>$row[id_berita]</td>
                        <td>$row[judul]</td>
                        <td>$row[deskripsi]</td>
                        <td>$row[image]</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='admin-update-berita.php?id=$row[id_berita]'>Update</a>
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