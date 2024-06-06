<?php
require_once 'connection.php';
$sql = "SELECT nama FROM user WHERE email = 'admin@gmail.com'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$adminName = ($user !== null) ? $user['nama'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VolunTrek - Admin Header</title>

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
            border-bottom: 3px solid blue;
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
                        <a href="admin-index.php" class="navbar-link">Home</a>
                    </li>

                    <li>
                        <a href="admin-volunteer.php" class="navbar-link">Volunteer</a>
                    </li>

                    <li>
                        <a href="admin-berita.php" class="navbar-link">Berita</a>
                    </li>

                </ul>

                <div class="header-actions">
                    <span>
                        <?php echo $adminName; ?>
                    </span>
                    <a href="logout.php" class="header-action-link">Logout</a>
                </div>
            </nav>

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