<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VolunTrek - About Us</title>

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="./assets/css/about-us.css">

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
        - #SERVICE
    -->

      <section class="section service" aria-labelledby="service-label">
        <div class="container">

          <p class="section-subtitle" id="service-label">VolunTrek</p>

          <h2 class="h2 section-title">
            Website Informasi Kegiatan Volunteer atau Relawan di Universitas Jember.
          </h2>

          <div class="service-image">
            <img src="./assets/images/us.jpg" alt="volunteer art">
          </div>

          <ul class="grid-list">

            <li>
              <div class="service-card">

                <div class="card-icon">
                  <ion-icon name="call-outline" aria-hidden="true"></ion-icon>
                </div>

                <h3 class="h4 card-title">24/7 Support</h3>

                <p class="card-text">
                  Tim dukungan pelanggan kami selalu siap membantu Anda. Jika Anda memiliki pertanyaan, masukan, atau
                  memerlukan bantuan, jangan ragu untuk menghubungi kami kapan pun.
                  Kami berkomitmen untuk memberikan pengalaman sukarelawan yang mulus dan bermakna.
                </p> <br>

              </div>
            </li>

            <li>
              <div class="service-card">

                <div class="card-icon">
                  <ion-icon name="shield-checkmark-outline" aria-hidden="true"></ion-icon>
                </div>

                <h3 class="h4 card-title">Membangun Masa Depan Penuh Kebaikan</h3>

                <p class="card-text">
                  Visi kami adalah menciptakan dunia di mana setiap individu memiliki akses dan motivasi untuk
                  berkontribusi dalam membentuk kebaikan.
                  Misi kami adalah menyediakan platform yang memudahkan orang untuk menemukan dan berpartisipasi dalam
                  kegiatan sukarela yang bermakna.
                </p>

              </div>
            </li>

            <li>
              <div class="service-card">

                <div class="card-icon">
                  <ion-icon name="cloud-download-outline" aria-hidden="true"></ion-icon>
                </div>

                <h3 class="h4 card-title">Inovatif dan Berdedikasi</h3>

                <p class="card-text">
                  Kami adalah tim yang berkomitmen untuk menciptakan solusi inovatif dalam mendukung kegiatan
                  sukarelawan. Dengan keahlian yang beragam dan semangat yang tinggi,
                  kami bersatu untuk membuat perbedaan positif dalam dunia sukarela.
                </p> <br> <br>

              </div>
            </li>

            <li>
              <div class="service-card">

                <div class="card-icon">
                  <ion-icon name="pie-chart-outline" aria-hidden="true"></ion-icon>
                </div>

                <h3 class="h4 card-title">Integritas, Kolaborasi, dan Kepedulian</h3>

                <p class="card-text">
                  Kami menghargai nilai-nilai seperti integritas, kolaborasi, dan kepedulian dalam setiap langkah yang
                  kami ambil.
                  Nilai-nilai ini membimbing kami untuk membangun lingkungan yang inklusif dan memberdayakan komunitas
                  sukarelawan.
                </p> <br>


              </div>
            </li>

          </ul>

        </div>
      </section>


    </article>
  </main>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <?php include "footer.php"; ?>
</body>