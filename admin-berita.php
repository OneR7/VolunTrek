<?php
require_once 'connection.php';

// Define the number of items per page
$itemsPerPage = 9;

// Get the current page number
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $itemsPerPage;

$sql = "SELECT * FROM berita ORDER BY id_berita DESC
        LIMIT $offset, $itemsPerPage";

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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Berita</title>

    <!-- 
    - favicon
  -->
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">

    <!-- 
    - custom css link
  -->
    <link rel="stylesheet" href="./assets/css/admin-berita.css">

    <!-- 
    - google font link
  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Mulish:wght@600;700;900&family=Quicksand:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
        }

        .pagination a.active {
            background-color: var(--violet-blue-crayola);
            color: white;
        }

        .pagination a:hover:not(.active) {
            background-color: var(--violet-blue-crayola);
        }
    </style>

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

                    <h2 class="h2 section-subtitle">Latest News</h2>

                    <p class="section-text">
                        Dengan menambahkan berita volunteer, kita dapat membantu kita dapat membantu meningkatkan
                        kesadaran masyarakat akan pentingnya kegiatan volunteer!!
                    </p>

                    <div action="" class="title-wrapper">
                        <button type="#" class="btn btn-primary" onclick="window.location.href='admin-add-berita.php'"
                            style="margin-right: 5%">Add Berita</button>
                        <button type="#" class="btn btn-primary"
                            onclick="window.location.href='admin-update-berita.php'" style="margin-left: 5%">Edit
                            Berita</button>
                    </div>

                    <ul class="blog-list">
                        <?php
                        if ($berita_result->num_rows > 0):
                            while ($row = $berita_result->fetch_assoc()):
                                ?>
                                <li style="margin-bottom: 30px">
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

                                        <a href="detail-berita.php?id=<?php echo $row['id_berita']; ?>" class="blog-link-btn">
                                            <span>Baca Selengkapnya</span>

                                            <ion-icon name="chevron-forward-outline"></ion-icon>
                                        </a>

                                    </div>
                                </li>

                                <?php
                            endwhile;
                        else:
                            echo "<p>Tidak ada berita ditemukan</p>";
                        endif;
                        ?>

                    </ul>

                    <div class="pagination">
                        <?php
                        $sql2 = "SELECT COUNT(*) AS total FROM berita";
                        $result = $conn->query($sql2);
                        $row = $result->fetch_assoc();
                        $totalPages = ceil($row['total'] / $itemsPerPage);

                        // Previous page link
                        if ($page > 1) {
                            echo '<a href="?page=' . ($page - 1) . '">&laquo;</a>';
                        }

                        // Numbered pagination links
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo '<a href="?page=' . $i . '"';
                            echo ($page == $i) ? ' class="active"' : '';
                            echo '>' . $i . '</a>';
                        }

                        // Next page link
                        if ($page < $totalPages) {
                            echo '<a href="?page=' . ($page + 1) . '">&raquo;</a>';
                        }
                        ?>
                    </div>

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
                        deleteBerita(beritaId);
                    }
                });
            });

            function deleteBerita(beritaId) {
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        window.location.reload();
                    }
                };
                xhr.open('GET', 'delete-berita.php?id=' + beritaId, true);
                xhr.send();
            }
        });
    </script>

    <?php include "footer.php"; ?>
</body>
<!-- 
    - ionicon link
-->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</html>