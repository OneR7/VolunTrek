<?php
session_start();
include 'connection.php'; // Include the database connection file

// Function to check if a user is logged in
function isLoggedIn()
{
  return isset($_SESSION["username"]);
}

// Function to get the username if logged in
function getUsername()
{
  return isLoggedIn() ? $_SESSION["username"] : "";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VolunTrek - Header</title>

  <!-- 
    - favicon
  -->
  <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="./assets/css/header.css">

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Mulish:wght@600;700;900&family=Quicksand:wght@400;500;600;700&display=swap"
    rel="stylesheet">
  <style>
    .navbar-link.active {
      color: var(--violet-blue-crayola:);
      /* Warna teks navbar-link aktif */
      border-bottom: 3px solid blue;
      /* Garis bawah navbar-link aktif */
    }
  </style>
</head>

<body>

  <!-- 
    - HEADER
  -->

  <header class="header" data-header>
    <div class="container">

      <a href="#" class="logo">
        <h1><b>VolunTrek</b></h1>
      </a>

      <button class="menu-toggle-btn" data-nav-toggle-btn>
        <ion-icon name="menu-outline"></ion-icon>
      </button>

      <nav class="navbar">
        <ul class="navbar-list">

          <li>
            <a href="index.php" class="navbar-link">Home</a>
          </li>

          <li>
            <a href="volunteer.php" class="navbar-link">Volunteer</a>
          </li>

          <li>
            <a href="berita.php" class="navbar-link">Berita</a>
          </li>

          <li>
            <a href="panduan.php" class="navbar-link">Panduan</a>
          </li>

          <li>
            <a href="about-us.php" class="navbar-link">About Us</a>
          </li>

        </ul>

        <div class="header-actions">
          <?php if (isLoggedIn()): ?>
            <?php if (getUsername() === 'admin@gmail.com'): ?>
              <?php
              // Ambil nama admin dari database
              $sql = "SELECT nama FROM user WHERE email = 'admin@gmail.com'";
              $result = $conn->query($sql);
              $user = $result->fetch(PDO::FETCH_ASSOC);
              $adminName = $user['nama'];
              ?>
              <span>
                <?php echo $adminName; ?>
              </span>
              <a href="logout.php" class="header-action-link">Logout</a>
            <?php else: ?>
              <span>
                <?php echo getUsername(); ?>
              </span>
              <a href="logout.php" class="header-action-link">Logout</a>
            <?php endif; ?>
          <?php else: ?>
            <a href="login.php" class="header-action-link">Log in</a>
            <a href="registration.php" class="header-action-link">Registrasi</a>
          <?php endif; ?>
        </div>

      </div>
  </header>
  <script src="./assets/js/header.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Menandai navbar-link aktif berdasarkan halaman saat ini
      var currentPage = window.location.pathname.split('/').pop();
      var navbarLinks = document.querySelectorAll('.navbar-link');

      navbarLinks.forEach(function (link) {
        var linkPage = link.getAttribute('href').split('/').pop();
        if (linkPage === currentPage) {
          link.classList.add('active');
        }
      });
    });
  </script>
</body>
</html>
