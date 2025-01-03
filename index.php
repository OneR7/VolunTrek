<?php
require_once 'connection.php'; // Include your database connection file
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = '6a8f2e4c923e46ff1e8fa10d8a0f8b4d2f5c3e78e0b1a25f8e2d3f4c5a6b7c8d';

if (isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];
    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
    } catch (Exception $e) {
        header('location:login.php');
        exit;
    }
} else {
    header('location:login.php');
    exit;
}

$sql = "SELECT * FROM berita ORDER BY id_berita DESC LIMIT 3";
$berita_result = $conn->query($sql);

$sql2 = "SELECT * FROM volunteer 
        INNER JOIN status ON volunteer.status_id_status = status.id_status
        INNER JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
        INNER JOIN tipe_kegiatan ON volunteer.tipe_kegiatan_tipe_kegiatan_id = tipe_kegiatan.id_tipe 
        ORDER BY id_volunteer DESC 
        LIMIT 4";

$volunteer_result = $conn->query($sql2);

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
  <link rel="stylesheet" href="./assets/css/style.css">

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
  <?php include "header.php"; ?>

  <main>
    <article>

      <!-- 
        - HERO
      -->

      <section class="hero" id="hero">
        <div class="container">

          <div class="hero-content">
            <h1 class="h1 hero-title">Gain Experience</h1>

            <p class="hero-text">
              Join us and make a positive impact on the world.
            </p>

            <p class="form-text">
              <span>🥳</span> Ambil peran jadi relawan. Ubah niat baik jadi aksi baik hari ini!!!
            </p>

            <div action="" class="title-wrapper">
              <a href="login.php" class="btn btn-primary">Join With Us</a>
            </div>
          </div>

          <figure class="hero-banner">
            <img src="./assets/images/cd.jpg" alt="Hero image">
          </figure>

        </div>
      </section>





      <!-- 
        - ABOUT
      -->

      <section class="about">
        <div class="container">

          <div class="about-content">

            <div class="about-icon">
              <ion-icon name="cube"></ion-icon>
            </div>

            <h2 class="h2 about-title">Why Volunteer ?</h2>

            <p class="about-text">
              Mengikuti kegiatan sukarela membawa sejumlah manfaat yang luas, tidak hanya bagi masyarakat yang dilayani,
              tetapi juga bagi diri sukarelawan sendiri.
            </p>

            <div class="title-wrapper">
              <a href="panduan.php" class="btn btn-primary">Learn More</a>
            </div>

          </div>

          <ul class="about-list">

            <li>
              <div class="about-card">

                <div class="card-icon">
                  <ion-icon name="thumbs-up"></ion-icon>
                </div>

                <h3 class="h3 card-title">Social Connect</h3>

                <p class="card-text">
                  Temukan teman-teman seidealmu dan bangun hubungan yang berarti selama perjalanan sukarelawan.
                </p>

              </div>
            </li>

            <li>
              <div class="about-card">

                <div class="card-icon">
                  <ion-icon name="trending-up"></ion-icon>
                </div>

                <h3 class="h3 card-title">Gain Experience</h3>

                <p class="card-text">
                  Kesempatan untuk mempelajari keterampilan baru dan mendapatkan pengalaman berharga
                </p>

              </div>
            </li>

            <li>
              <div class="about-card">

                <div class="card-icon">
                  <ion-icon name="shield-checkmark"></ion-icon>
                </div>

                <h3 class="h3 card-title">Create Immadate Impact</h3>

                <p class="card-text">
                  Jadi agen perubahan dan lihat hasil langsung dari kontribusi kalian dalam membentuk dunia yang lebih
                  baik.
                </p>

              </div>
            </li>

            <li>
              <div class="about-card">

                <div class="card-icon">
                  <ion-icon name="server"></ion-icon>
                </div>

                <h3 class="h3 card-title">Contribute to Positive Goals</h3>

                <p class="card-text">
                  Terapkan skill dan bakat kalian untuk mendukung tujuan positif dan membuat perbedaan nyata di dunia.
                </p>

              </div>
            </li>

          </ul>

        </div>
      </section>


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

          <div class="btn-outline-container">
            <a href="berita.php" class="btn btn-outline">Learn More</a>
          </div>
        </div>
      </section>




      <!-- 
        - #PROJECT
      -->

      <section class="section project" aria-labelledby="project-label">
        <div class="container">

          <h2 class="h2 section-subtitle" id="project-label">Volunteer</h2>

          <p class="section-text">
            Ikuti kegiatan volunteer dan wujudkan cita-citamu untuk membuat dunia menjadi lebih baik.
          </p>

          <ul class="grid-list">
            <?php
            if ($volunteer_result->num_rows > 0):
              while ($row = $volunteer_result->fetch_assoc()):
                ?>
                <li>
                  <div class="project-card">

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

                        <a href="detail-volunteer.php?id=<?php echo $row['id_volunteer']; ?>" class="read-more-btn">Read
                          More</a>
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
          <a class="btn btn-outline" href="volunteer.php">Learn More</a>
        </div>
      </section>



      <!-- 
        - #FAQ
      -->

      <section class="section faq" aria-label="frequently asked questions">
        <div class="container">

          <div class="title-wrapper">
            <h2 class="h2 section-faq-title">Discover Frequently Asked Questions?</h2>

            <a href="registration.php" class="btn btn-primary">Work Together</a>
          </div>

          <ul class="grid-list2">

            <li>
              <div class="faq-card">

                <button class="card-action" data-accordion-action>
                  <h3 class="h3 card-title">
                    01. Apa itu situs web VolunTrek ini?
                  </h3>

                  <div class="action-icon">
                    <ion-icon name="add-outline" aria-hidden="true" class="open"></ion-icon>
                    <ion-icon name="remove-outline" aria-hidden="true" class="close"></ion-icon>
                  </div>
                </button>

                <div class="card-content">
                  <p>
                    Situs web ini adalah platform yang menghubungkan mahasiswa Universitas Jember yang ingin melakukan
                    kegiatan sukarela dengan organisasi atau proyek yang membutuhkan bantuan sukarela. Kami menyediakan
                    tempat bagi para sukarelawan dan organisasi untuk saling terhubung dan bekerja sama.
                  </p>
                </div>

              </div>
            </li>

            <li>
              <div class="faq-card">

                <button class="card-action" data-accordion-action>
                  <h3 class="h3 card-title">
                    02. Bagaimana cara saya bergabung sebagai volunteer atau sukarelawan?
                  </h3>

                  <div class="action-icon">
                    <ion-icon name="add-outline" aria-hidden="true" class="open"></ion-icon>
                    <ion-icon name="remove-outline" aria-hidden="true" class="close"></ion-icon>
                  </div>
                </button>

                <div class="card-content">
                  <p>
                    Untuk bergabung sebagai volunteer atau sukarelawan, cukup buka halaman register kami dan isi
                    formulir yang tersedia.
                    Setelah pendaftaran, Anda dapat mencari proyek atau kegiatan yang sesuai dengan minat dan
                    keterampilan Anda.
                  </p>
                </div>

              </div>
            </li>

            <li>
              <div class="faq-card">

                <button class="card-action" data-accordion-action>
                  <h3 class="h3 card-title">
                    03. Apakah saya harus membayar untuk bergabung sebagai sukarelawan?
                  </h3>

                  <div class="action-icon">
                    <ion-icon name="add-outline" aria-hidden="true" class="open"></ion-icon>
                    <ion-icon name="remove-outline" aria-hidden="true" class="close"></ion-icon>
                  </div>
                </button>

                <div class="card-content">
                  <p>
                    Tidak, bergabung sebagai sukarelawan di situs web kami sepenuhnya gratis.
                    Kami berusaha menciptakan lingkungan yang terbuka dan terjangkau bagi mahasiswa Universitas Jember
                    yang ingin berkontribusi.
                  </p>
                </div>

              </div>
            </li>

            <li>
              <div class="faq-card">

                <button class="card-action" data-accordion-action>
                  <h3 class="h3 card-title">
                    04. Bagaimana cara mencari proyek atau kegiatan volunteer yang sesuai?
                  </h3>

                  <div class="action-icon">
                    <ion-icon name="add-outline" aria-hidden="true" class="open"></ion-icon>
                    <ion-icon name="remove-outline" aria-hidden="true" class="close"></ion-icon>
                  </div>
                </button>

                <div class="card-content">
                  <p>
                    Anda dapat menggunakan fitur filter volunteer di situs web kami untuk menemukan proyek yang sesuai
                    dengan minat, lokasi, kriteria atau keterampilan Anda.
                  </p>
                </div>

              </div>
            </li>

            <li>
              <div class="faq-card">

                <button class="card-action" data-accordion-action>
                  <h3 class="h3 card-title">
                    05. Bisakah saya menjadi volunteer atau sukarelawan secara virtual?
                  </h3>

                  <div class="action-icon">
                    <ion-icon name="add-outline" aria-hidden="true" class="open"></ion-icon>
                    <ion-icon name="remove-outline" aria-hidden="true" class="close"></ion-icon>
                  </div>
                </button>

                <div class="card-content">
                  <p>
                    Ya, tentu bisa. Banyak proyek atau kegaiatan volunteer yang memungkinkan partisipasi secara virtual.
                    Anda dapat mencari proyek yang dapat Anda lakukan dari jarak jauh, sehingga memungkinkan
                    fleksibilitas yang lebih besar untuk sukarelawan yang memiliki keterbatasan geografis.
                  </p>
                </div>

              </div>
            </li>

            <li>
              <div class="faq-card">

                <button class="card-action" data-accordion-action>
                  <h3 class="h3 card-title">
                    06. Apa manfaatnya menjadi volunteer atau sukarelawan di website VolunTrek ini?
                  </h3>

                  <div class="action-icon">
                    <ion-icon name="add-outline" aria-hidden="true" class="open"></ion-icon>
                    <ion-icon name="remove-outline" aria-hidden="true" class="close"></ion-icon>
                  </div>
                </button>

                <div class="card-content">
                  <p>
                    Menjadi sukarelawan dapat memberikan banyak manfaat, termasuk pengembangan keterampilan, membangun
                    jaringan sosial, dan memberikan rasa pencapaian.
                    Selain itu, ini adalah cara yang bagus untuk memberikan kembali kepada komunitas dan membuat
                    perbedaan positif.
                  </p>
                </div>

              </div>
            </li>

          </ul>

        </div>
      </section>

    </article>
  </main>

  <!-- 
    - custom js link
  -->
  <script src="./assets/js/script2.js"></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <?php include "footer.php"; ?>
</body>

</html>