<?php
// Define the current page number
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Call the external API to fetch news data
$api_url = "http://localhost/voluntrek_coba/api_admin-berita.php?page=$page"; // Update URL accordingly
$news_data = file_get_contents($api_url);

// Check if API request was successful and data is retrieved
if ($news_data !== false) {
    // Decode the JSON response
    $articles = json_decode($news_data, true);
} else {
    // Failed to fetch news data from the API
    $articles = []; // Set articles to empty array
}
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
            <section class="blog" id="blog">
                <div class="container">
                    <h2 class="h2 section-subtitle">Latest News</h2>
                    <p class="section-text">Temukan berita volunteer Universitas Jember hanya di sini !!!!</p>
                    <ul class="blog-list">
                        <?php if (!empty($articles)) : ?>
                            <?php foreach ($articles as $article) : ?>
                                <li style="margin-bottom: 30px">
                                    <div class="blog-card">
                                        <figure class="blog-banner">
                                            <img src="<?php echo $article['image']; ?>" alt="<?php echo $article['judul']; ?>">
                                        </figure>
                                        <h3 class="blog-title"><?php echo $article['judul']; ?></h3>
                                        <p class="blog-text"><?php echo substr($article['deskripsi'], 0, 150) . '...'; ?></p>
                                        <a href="detail-berita.php?id=<?php echo $article['id_berita']; ?>" class="blog-link-btn">
                                            <span>Baca Selengkapnya</span>
                                            <ion-icon name="chevron-forward-outline"></ion-icon>
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>Tidak ada berita ditemukan</p>
                        <?php endif; ?>
                    </ul>

                    <!-- Pagination remains the same -->

                </div>
            </section>
        </article>
    </main>

    <?php include "footer.php"; ?>
</body>
</html>