<?php
require_once 'connection.php';

// Define the number of items per page
$itemsPerPage = 12;

// Get the current page number
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Initialize filter conditions
$filterConditions = "";

// Check if the form is submitted and fakultas is selected
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filter'])) {
    if (isset($_POST['fakultas']) && $_POST['fakultas'] != "Pilih fakultas...") {
        $fakultas = mysqli_real_escape_string($conn, $_POST['fakultas']);
        $filterConditions = "&fakultas=$fakultas";
    }
}

// Construct API URL with pagination and filter parameters
$api_url = "http://localhost/voluntrek_coba/api_volunteer.php?page=$page&itemsPerPage=$itemsPerPage";
if ($filterConditions !== "") {
    $api_url .= $filterConditions;
}

// Use cURL to fetch data from the API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

$volunteer_result = $data['volunteers'];
$totalPages = $data['total_pages'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VolunTrek - Volunteer</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">

    <!-- custom css link -->
    <link rel="stylesheet" href="./assets/css/volunteer.css">

    <!-- google font link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@600;700;900&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

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

        body {
            background-color: aquamarine;
        }

        .popup {
            width: 500px;
            height: 300px;
            position: fixed;
            border-radius: 6px;
            top: 0%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.1);
            background-color: white;
            visibility: hidden;
            transition: transform 0.4s, top 0.4s;
            padding: 20px 25px 15px;
            z-index: 9999;
        }

        .open-popup {
            visibility: visible;
            top: 50%;
            transform: translate(-50%, -50%) scale(1);
        }

        .btn-close {
            font-size: 12px;
        }

        .form-label {
            font-size: 15px;
        }

        select.form-select option[selected] {
            color: #8b8181;
        }

        button.buttonfilter {
            width: auto;
            font-size: 15px;
            padding-top: 7px;
            padding-bottom: 7px;
            padding-left: 15px;
            padding-right: 15px;
            border-radius: 3px;
        }

        .btn-outline-primary {
            margin-left: 94%;
            margin-bottom: 20px;
        }
    </style>

</head>

<body>
    <main>
        <?php include "header.php"; ?>
        <article>
            <section class="section project" aria-labelledby="project-label" style="margin-top: 7%">
                <div class="container">
                    <h2 class="h2 section-subtitle" id="project-label">Volunteer</h2>
                    <p class="section-text">
                        Ikuti kegiatan volunteer dan wujudkan cita-citamu untuk membuat dunia menjadi lebih baik.
                    </p>

                    <!-- Filter Popup -->
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>"> <!-- Form action points to the same page -->
                    <div class="container">
                        <!-- Filter Button -->
                        <button type="button" class="btn btn-outline-primary buttonfilter" onclick="openPopup()">Fakultas</button>
                        <!-- Filter Popup Content -->
                        <div class="popup" id="popup">
                            <div class="row align-items-start">
                                <hr class="mt-2">
                            </div>

                            <form method="POST" action="">
                                <div class="row align-items-start">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label for="exampleFormControlInput1" class="form-label">Fakultas</label>
                                            <select name="fakultas" id="fakultas" class="form-select form-select-sm" aria-label="Small select example">
                                                <option selected>Pilih fakultas...</option>
                                                <?php
                                                // Fetch fakultas data from database and generate options
                                                $result = mysqli_query($conn, "SELECT * FROM fakultas");
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<option value='{$row['nama_fakultas']}'>{$row['nama_fakultas']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row align-items-start">
                                    <div class="col d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary buttonfilter" onclick="closePopup()">Batal</button>
                                        <input type="submit" class="btn btn-primary ms-2 buttonfilter" name="filter" value="Filter">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </form>
                    <!-- End Filter Popup -->

                    <!-- Volunteer Cards -->
                    <ul class="grid-list">
                        <?php
                        if ($volunteer_result && count($volunteer_result) > 0):
                            foreach ($volunteer_result as $row):
                        ?>
                        <!-- Volunteer Card -->
                        <li style="flex: 0 0 calc(25% - 20px); margin-bottom: 30px">
                            <div class="project-card">
                                <!-- Card Banner -->
                                <figure class="card-banner img-holder" style="--width: 560; --height: 350;">
                                    <img src="<?php echo $row['image'] ?>" width="560" height="350" loading="lazy" alt="<?php echo $row['nama_kegiatan'] ?>" class="img-cover">
                                </figure>
                                <!-- Card Content -->
                                <div class="card-content">
                                    <!-- Card Meta Info -->
                                    <ul class="card-meta-list2">
                                        <li>
                                            <a style="align-items: left" href="#" class="card-meta-link">
                                                <ion-icon name="person"></ion-icon>
                                                <span>by: <?php echo $row['nama_penyelenggara'] ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- Card Title -->
                                    <h3 class="h3">
                                        <a href="#" class="card-title"><?php echo $row['nama_kegiatan'] ?></a>
                                    </h3>
                                    <!-- Faculty -->
                                    <p class="card-text"><?php echo $row['nama_fakultas'] ?></p>
                                    <!-- Status -->
                                    <ul class="card-meta-list">
                                        <li class="card-meta-item">
                                            <ion-icon name="document-text-outline"></ion-icon>
                                            <span class="meta-text"><?php echo $row['nama_status'] ?></span>
                                        </li>
                                    </ul>
                                    <!-- Additional Info -->
                                    <div class="project-content-bottom">
                                        <!-- Publish Date -->
                                        <div class="publish-date">
                                            <ion-icon name="calendar"></ion-icon>
                                            <time datetime="<?php echo $row['tanggal_kegiatan'] ?>">
                                                <?php echo date("M d, Y", strtotime($row['tanggal_kegiatan'])) ?>
                                            </time>
                                        </div>
                                        <!-- Read More Button -->
                                        <a href="detail-volunteer.php?id=<?php echo $row['id_volunteer']; ?>" class="read-more-btn">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- End Volunteer Card -->
                        <?php
                            endforeach;
                        else:
                            echo "<p>Tidak ada data ditemukan</p>";
                        endif;
                        ?>
                    </ul>
                    <!-- End Volunteer Cards -->

                    <!-- Pagination -->
                    <div class="pagination">
                        <?php
                        // Previous page link
                        if ($page > 1) {
                            echo '<a href="?page=' . ($page - 1) . '">&laquo;</a>';
                        }

                        // Numbered pagination links
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo '<a href="?page=' . $i . $filterConditions . '"';
                            echo ($page == $i) ? ' class="active"' : '';
                            echo '>' . $i . '</a>';
                        }

                        // Next page link
                        if ($page < $totalPages) {
                            echo '<a href="?page=' . ($page + 1) . $filterConditions . '">&raquo;</a>';
                        }
                        ?>
                    </div>
                    <!-- End Pagination -->
                </div>
            </section>

            <?php include "footer.php"; ?>
        </article>
    </main>

    <!-- JavaScript for handling filter popup -->
    <script type="text/javascript">
    function openPopup() {
        document.getElementById("popup").classList.add("open-popup");
    }

    function closePopup() {
        document.getElementById("popup").classList.remove("open-popup");
    }
</script>

</body>
</html>
