<?php
require_once 'connection.php';

// Define the number of items per page
$itemsPerPage = 12;

// Get the current page number
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for the SQL query
$offset = ($page - 1) * $itemsPerPage;

$sql = "SELECT * FROM volunteer 
        INNER JOIN status ON volunteer.status_id_status = status.id_status
        INNER JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
        INNER JOIN tipe_kegiatan ON volunteer.tipe_kegiatan_tipe_kegiatan_id = tipe_kegiatan.id_tipe 
        ORDER BY id_volunteer DESC
        LIMIT $offset, $itemsPerPage";

$volunteer_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VolunTrek - Volunteer</title>

    <!-- 
    - favicon
  -->
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">

    <!-- 
    - custom css link
  -->
    <link rel="stylesheet" href="./assets/css/volunteer.css">

    <!-- 
    - google font link
  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Mulish:wght@600;700;900&family=Quicksand:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


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

                    <div class="container">
                        <button type="button" class="btn btn-outline-primary buttonfilter"
                            onclick="openPopup()">Fakultas</button>
                        <div class="popup" id="popup">
                            <div class="row align-items-start">

                                <hr class="mt-2">
                            </div>

                            <form method="POST" action="">
                                <div class="row align-items-start">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label for="exampleFormControlInput1" class="form-label">Fakultas</label>
                                            <select name="fakultas" id="fakultas" class="form-select form-select-sm"
                                                aria-label="Small select example">
                                                <option selected>Pilih fakultas...</option>

                                                <?php
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
                                        <button type="button" class="btn btn-outline-secondary buttonfilter"
                                            onclick="closePopup()">Batal</button>
                                        <input type="submit" class="btn btn-primary ms-2 buttonfilter" name="filter"
                                            value="Filter">
                                    </div>
                                </div>
                        </div>
                    </div>

                    <?php
                    require_once 'connection.php';

                    function getVolunteerData($conn, $offset, $itemsPerPage, $filterConditions = '')
                    {
                        $sql = "SELECT * FROM volunteer 
                 INNER JOIN status ON volunteer.status_id_status = status.id_status
                 INNER JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
                 INNER JOIN tipe_kegiatan ON volunteer.tipe_kegiatan_tipe_kegiatan_id = tipe_kegiatan.id_tipe 
                 $filterConditions
                 ORDER BY id_volunteer DESC
                 LIMIT ?, ?";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ii", $offset, $itemsPerPage);
                        $stmt->execute();

                        return $stmt->get_result();
                    }

                    function generateFilterOptions($conn, $table, $valueColumn, $labelColumn)
                    {
                        $result = mysqli_query($conn, "SELECT * FROM $table");
                        $options = "<option selected>Pilih $table...</option>";

                        while ($row = mysqli_fetch_assoc($result)) {
                            $options .= "<option value='{$row[$valueColumn]}'>{$row[$labelColumn]}</option>";
                        }

                        return $options;
                    }

                    $itemsPerPage = 12;

                    // Get the current page number
                    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

                    // Calculate the offset for the SQL query
                    $offset = ($page - 1) * $itemsPerPage;

                    // Initialize the result variable
                    $volunteer_result = null;

                    // Filter conditions
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filter'])) {
                        $filterConditions = array();

                        if (isset($_POST['fakultas']) && $_POST['fakultas'] != "Pilih fakultas...") {
                            $fakultas = mysqli_real_escape_string($conn, $_POST['fakultas']);
                            $filterConditions[] = "fakultas.nama_fakultas = '$fakultas'";
                        }
                        $filterConditionsString = !empty($filterConditions) ? " AND " . implode(" AND ", $filterConditions) : "";

                        // Get volunteer data based on filter conditions
                        $volunteer_result = getVolunteerData($conn, $offset, $itemsPerPage, $filterConditionsString);

                        // Check if the query is executed successfully
                        if (!$volunteer_result) {
                            echo "Query failed: " . mysqli_error($conn);
                        }
                    } else {
                        // If the form is not submitted, get volunteer data without filters
                        $volunteer_result = getVolunteerData($conn, $offset, $itemsPerPage);
                    }

                    ?>

                    <ul class="grid-list">

                        <?php
                        $count = 0;
                        if ($volunteer_result or $result !== null && $result or $volunteer_result->num_rows > 0):
                            while ($row = $volunteer_result->fetch_assoc() or $row = $result->fetch_assoc()):
                                ?>

                                <li style="flex: 0 0 calc(25% - 20px); margin-bottom: 30px">
                                    <div class="project-card">

                                        <figure class="card-banner img-holder" style="--width: 560; --height: 350;">
                                            <img src="<?php echo $row['image'] ?>" width="560" height="350" loading="lazy"
                                                alt="<?php echo $row['nama_kegiatan'] ?>" class="img-cover">
                                        </figure>

                                        <div class="card-content">

                                            <ul class="card-meta-list2">

                                                <li>
                                                    <a style="align-items: left" href="#" class="card-meta-link">
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

                                                <a href="detail-volunteer.php?id=<?php echo $row['id_volunteer']; ?>"
                                                    class="read-more-btn">Read
                                                    More</a>
                                            </div>

                                        </div>

                                    </div>
                                </li>

                                <?php
                                $count++;
                                if ($count % $itemsPerPage == 0) {
                                    echo '</ul><ul class="grid-list">';
                                }
                            endwhile;
                        else:
                            echo "<p>Tidak ada data ditemukan</p>";
                        endif;
                        ?>

                    </ul>
                    <div class="pagination">
                        <?php
                        $filterConditions = "";

                        // Check if the form is submitted and fakultas is selected
                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filter'])) {
                            if (isset($_POST['fakultas']) && $_POST['fakultas'] != "Pilih fakultas...") {
                                $fakultas = mysqli_real_escape_string($conn, $_POST['fakultas']);
                                $filterConditions = " AND fakultas.nama_fakultas = '$fakultas'";
                            }
                        }



                        // Use the filter condition in SQL query
                        $sql3 = "SELECT COUNT(*) AS total FROM volunteer 
                      INNER JOIN status ON volunteer.status_id_status = status.id_status
                      INNER JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
                      INNER JOIN tipe_kegiatan ON volunteer.tipe_kegiatan_tipe_kegiatan_id = tipe_kegiatan.id_tipe 
                      WHERE 1 $filterConditions"; // Add the filter conditions here
                        
                        $result = $conn->query($sql3);
                        $row = $result->fetch_assoc();
                        $totalPages = ceil($row['total'] / $itemsPerPage);

                        // SQL2 is only needed if there are no filter conditions
                        if (empty($filterConditions)) {
                            $sql2 = "SELECT COUNT(*) AS total FROM volunteer";
                            $result = $conn->query($sql2);
                            $row = $result->fetch_assoc();
                            $totalPages = ceil($row['total'] / $itemsPerPage);
                        }

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

            <?php include "footer.php"; ?>
        </article>
    </main>


    <script type="text/javascript">
        $(document).ready(function () {
            $("#fakultas").on('change', function () {
                var value = $(this).val();
                // alert(value);
                $.ajax({
                    url: "fetch.php",
                    type: "POST",
                    data: { fakultas: value },
                    beforeSend: function () {
                        $(".container").html("<span>Working...</span>");
                    },
                    success: function () {
                        $(".container").html(data);
                    }
                })
            })
        })
    </script>
</body>

<script src="./assets/js/filter.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>

</html>