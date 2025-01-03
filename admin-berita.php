<?php
// Define the base URL of the external API
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$api_url = "http://localhost/voluntrek_coba/api_admin-berita.php?page=$page";

// Fetch data from the external API
$news_data = file_get_contents($api_url);

// Check if API request was successful and data is retrieved
if ($news_data !== false) {
    // Decode the JSON response
    $articles = json_decode($news_data, true);

    // Check if news articles are present in the response
    if (is_array($articles) && !empty($articles)) {
        // Data fetched successfully
    } else {
        // No articles found
        $articles = [];
    }
} else {
    // Failed to fetch news data from the API
    $articles = [];
}

// Define the number of items per page
$itemsPerPage = 9;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Berita</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">

    <!-- custom css link -->
    <link rel="stylesheet" href="./assets/css/admin-berita.css">

    <!-- google font link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@600;700;900&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

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
            <section class="blog" id="blog">
                <div class="container">
                    <h2 class="h2 section-subtitle">Latest News</h2>
                    <p class="section-text">
                        Dengan menambahkan berita volunteer, kita dapat membantu kita dapat membantu meningkatkan
                        kesadaran masyarakat akan pentingnya kegiatan volunteer!!
                    </p>

                    <div class="title-wrapper">
                        <button type="#" class="btn btn-primary" onclick="window.location.href='admin-add-berita.php'" style="margin-right: 5%">Add Berita</button>
                        <button type="#" class="btn btn-primary" onclick="window.location.href='admin-update-berita.php'" style="margin-left: 5%">Edit Berita</button>
                    </div>

                    <ul class="blog-list">
                        <?php
                        if (!empty($articles)):
                            foreach ($articles as $row):
                        ?>
                        <div class="blog-card">
                            <button class="delete-btn" data-berita-id="<?php echo $row['id_berita']; ?>" style="color: red">
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

                            <a href="#" class="blog-link-btn">
                                <span>Baca Selengkapnya</span>
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                            </a>
                        </div>
                        <?php
                            endforeach;
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
                xhr.open('DELETE', `http://localhost/voluntrek_coba/api_admin-berita.php?id_berita=${beritaId}`, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('id_berita=' + beritaId);
            }
        });
    </script>

    <?php include "footer.php"; ?>
</body>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</html>