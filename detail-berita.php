<?php
require_once 'connection.php';

$sql = "SELECT * FROM berita ORDER BY id_berita DESC LIMIT 3";
$berita_result = $conn->query($sql);

// Get berita ID from the URL
$beritaId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil berita details from the database
$sql2 = "SELECT * FROM berita WHERE id_berita = $beritaId";
$result = $conn->query($sql2);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $judul = $row['judul'];
  $deskripsi = $row['deskripsi'];
  $image = $row['image'];
} else {
  // Redirect to an error page or handle as needed
  header("Location: error.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Detail Berita -
    <?php echo $judul; ?>
  </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">
  <link rel="stylesheet" href="./assets/css/detail-berita.css" />
</head>

<style>
  .custom-img {
    width: 100%;
    height: 100%;
  }
</style>

<body>
  <?php include "header.php"; ?>
  <div class="container">
    <div class="row align-items-start">
      <div class="col-2"></div>
      <div class="col-8">
        <h1 style="font-size: 50px; margin-top: 160px">
          <?php echo $judul; ?>
        </h1>
      </div>
      <div class="col-2"></div>
    </div>
  </div><br>

  <div class="container text-center">
    <div class="row align-items-start">
      <div class="col-2"></div>
      <div class="col-8">
        <img src="<?php echo $image; ?>" class="img-fluid custom-img rounded" alt="<?php echo $judul; ?>" />
      </div>
      <div class="col-2"></div>
    </div>
  </div><br>

  <div class="container">
    <div class="row align-items-start">
      <div class="col-2"></div>
      <div class="col-8">
        <p class="custom-paragraph">
          <?php echo $deskripsi; ?>
        </p>
      </div>
      <div class="col-2"></div>
    </div>
  </div>

  <div class="container text-center">
    <div class="row align-items-start">
      <div class="col-2"></div>
      <div class="col-8">
        -- Selesai --
      </div>
      <div class="col-2"></div>
    </div>
  </div>

  <div class="container text-center">
    <div class="row align-items-start">
      <div class="col-12">
        <hr>
      </div>
    </div>
  </div>

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

                <li>
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

                    <a href="#" class="blog-link-btn">
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
        </div>
      </section>

    </article>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>

  <!-- 
    - ionicon link
    -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <?php include "footer.php"; ?>
</body>

</html>