<?php
require_once 'connection.php';

$sql = "SELECT * FROM berita ORDER BY id_berita DESC
LIMIT 3";

$berita_result = $conn->query($sql);

if (isset($_GET['id'])) {
    $beritaId = $_GET['id'];

    // Perform the database deletion
    $deleteSql = "DELETE FROM berita WHERE id_berita = $beritaId";
    if ($conn->query($deleteSql) === TRUE) {
        echo "Berita berhasil dihapus";
    } else {
        echo "Error deleting berita: " . $conn->error;
    }
}

$sql2 = "SELECT * FROM volunteer 
        INNER JOIN status ON volunteer.status_id_status = status.id_status
        INNER JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
        INNER JOIN tipe_kegiatan ON volunteer.tipe_kegiatan_tipe_kegiatan_id = tipe_kegiatan.id_tipe 
        ORDER BY id_volunteer DESC
        LIMIT 4";

$volunteer_result = $conn->query($sql2);

if (isset($_GET['id'])) {
    $volunteerId = $_GET['id'];

    // Perform the database deletion
    $deleteSql = "DELETE FROM volunteer WHERE id_volunteer = $volunteerId";
    if ($conn->query($deleteSql) === TRUE) {
        echo "Volunteer deleted successfully";
    } else {
        echo "Error deleting volunteer: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VolunTrek - Landing page</title>

    <!-- 
    - favicon
  -->
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">

    <!-- 
    - custom css link
  -->
    <link rel="stylesheet" href="./assets/css/admin-index.css">

    <!-- 
    - google font link
  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Mulish:wght@600;700;900&family=Quicksand:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <?php include "admin-header.php"; ?>

    <main>
        <article>
            <!-- 
        - BLOG 
      -->

            <section class="blog" id="blog">
                <div class="container">

                    <h2 class="h2 section-subtitle">Edit Berita</h2>

                    <p class="section-text">

                    </p>

                    <ul class="blog-list">
                        <?php
                        if ($berita_result->num_rows > 0):
                            while ($row = $berita_result->fetch_assoc()):
                                ?>

                                <li>
                                    <div class="blog-card">

                                        <button class="delete-btn" data-berita-id="<?php echo $row['id_berita']; ?>"
                                            style="color: red">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </button>

                                        <figure class="blog-banner">
                                            <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['judul']; ?>">
                                        </figure>

                                        <h3 class="blog-title">
                                            <?php echo $row['judul']; ?>
                                        </h3>

                                        <p class="blog-text">
                                            <?php
                                            $deskripsi = $row['deskripsi'];
                                            if (strlen($deskripsi) > 150) {
                                                echo substr($deskripsi, 0, 150) . '...';
                                            } else {
                                                echo $deskripsi;
                                            }
                                            ?>
                                        </p>

                                        <a href="detail-berita.php?id=<?php echo $row['id_berita']; ?>" class="blog-link-btn">Baca Selengkapnya<ion-icon name="chevron-forward-outline"></ion-icon></a>

                                    </div>
                                </li>

                                <?php
                            endwhile;
                        else:
                            echo "<p>Tidak ada berita ditemukan</p>";
                        endif;
                        ?>

                    </ul>

                    <div class="btn-outline-container">
                        <a href="admin-berita.php" class="btn btn-outline">Learn More</a>
                    </div>
                </div>
            </section>




            <!-- 
        - #PROJECT
      -->

            <section class="section project" aria-labelledby="project-label">
                <div class="container">

                    <h2 class="h2 section-subtitle" id="project-label">Edit Volunteer</h2>

                    <p class="section-text">

                    </p>

                    <ul class="grid-list">
                        <?php
                        if ($volunteer_result->num_rows > 0):
                            while ($row = $volunteer_result->fetch_assoc()):
                                ?>
                                <li>
                                    <div class="project-card">

                                        <button class="delete-btn" data-volunteer-id="<?php echo $row['id_volunteer']; ?>"
                                            style="color: red">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </button>

                                        <figure class="card-banner img-holder" style="--width: 560; --height: 350;">
                                            <img src="<?php echo $row['image']; ?>" width="560" height="350" loading="lazy"
                                                alt="<?php echo $row['nama_kegiatan'] ?>" class="img-cover">
                                        </figure>

                                        <div class="card-content">

                                            <ul class="card-meta-list2">

                                                <li>
                                                    <a href="#" class="card-meta-link">
                                                        <ion-icon name="person"></ion-icon>

                                                        <span>by:
                                                            <?php echo $row['nama_penyelenggara'] ?>
                                                        </span>
                                                    </a>
                                                </li>

                                            </ul>

                                            <h3 class="h3">
                                                <a href="#" class="card-title">
                                                    <?php echo $row['nama_kegiatan'] ?>
                                                </a>
                                            </h3>

                                            <p class="card-text">
                                                <?php echo $row['nama_fakultas'] ?>
                                            </p>

                                            <ul class="card-meta-list">

                                                <li class="card-meta-item">
                                                    <ion-icon name="document-text-outline"></ion-icon>

                                                    <span class="meta-text">
                                                        <?php echo $row['nama_status'] ?>
                                                    </span>
                                                </li>

                                            </ul>

                                            <div class="project-content-bottom">
                                                <div class="publish-date">
                                                    <ion-icon name="calendar"></ion-icon>

                                                    <time datetime="<?php echo $row['tanggal_kegiatan'] ?>">
                                                        <?php echo date("M d, Y", strtotime($row['tanggal_kegiatan'])) ?>
                                                    </time>
                                                </div>

                                                <a href="detail-volunteer.php?id=<?php echo $row['id_volunteer']; ?>" class="read-more-btn">Read More</a>
                                            </div>

                                        </div>

                                    </div>
                                </li>
                                <?php
                            endwhile;
                        else:
                            echo "<p>Tidak ada data ditemukan</p>";
                        endif;
                        ?>
                    </ul>

                </div>
                <div class="btn-outline-container">
                    <a class="btn btn-outline" href="admin-volunteer.php">Learn More</a>
                </div>
            </section>
        </article>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const beritaId = this.getAttribute('data-berita-id');
                    if (confirm('Apakah kamu yakin ingin menghapus data berita ini?')) {
                        // Make an AJAX request to the backend to delete the volunteer
                        deleteBerita(beritaId);
                    }
                });
            });

            function deleteBerita(beritaId) {
                // Make an AJAX request to the backend PHP script
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Reload the page after successful deletion
                        window.location.reload();
                    }
                };
                xhr.open('GET', 'delete-berita.php?id=' + beritaId, true);
                xhr.send();
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const volunteerId = this.getAttribute('data-volunteer-id');
                    if (confirm('Apakah kamu yakin ingin menghapus data volunteer ini?')) {
                        // Make an AJAX request to the backend to delete the volunteer
                        deleteVolunteer(volunteerId);
                    }
                });
            });

            function deleteVolunteer(volunteerId) {
                // Make an AJAX request to the backend PHP script
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Reload the page after successful deletion
                        window.location.reload();
                    }
                };
                xhr.open('GET', 'delete-volunteer.php?id=' + volunteerId, true);
                xhr.send();
            }
        });
    </script>


    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <?php include "footer.php"; ?>
</body>