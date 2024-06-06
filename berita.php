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
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VolunTrek - Berita</title>

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="./assets/css/berita.css">

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
  <?php include "header.php"; ?>

  <main>
    <article>

      <!-- 
        - BLOG 
      -->

      <section class="blog" id="blog">
        <div class="container">

          <h2 class="h2 section-subtitle">Latest News</h2>

          <p class="section-text">
            Temukan berita volunteer Universitas Jember hanya di sini !!!!
          </p>

          <ul class="blog-list">
            <?php
            if ($berita_result->num_rows > 0):
              while ($row = $berita_result->fetch_assoc()):
                ?>
                <li style="margin-bottom: 30px">
                  <div class="blog-card">

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
            $sql = "SELECT COUNT(*) AS total FROM berita";
            $result = $conn->query($sql);
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

  <?php include "footer.php"; ?>
</body>
<!-- 
    - ionicon link
-->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</html>