<?php
require_once 'connection.php';


$itemsPerPage = 12;


$page = isset($_GET['page']) ? intval($_GET['page']) : 1;


$offset = ($page - 1) * $itemsPerPage;

$sql = "SELECT * FROM volunteer 
        INNER JOIN status ON volunteer.status_id_status = status.id_status
        INNER JOIN fakultas ON volunteer.fakultas_id_fakultas = fakultas.id_fakultas
        INNER JOIN tipe_kegiatan ON volunteer.tipe_kegiatan_tipe_kegiatan_id = tipe_kegiatan.id_tipe 
        ORDER BY id_volunteer DESC
        LIMIT $offset, $itemsPerPage";

$volunteer_result = $conn->query($sql);

if (isset($_GET['id'])) {
    $volunteerId = $_GET['id'];

    // Perform the database deletion
    $deleteSql = "DELETE FROM volunteer WHERE id_volunteer = $volunteerId";
    if ($conn->query($deleteSql) === TRUE) {
        echo "Volunteer deleted successfully";
    } else {
        echo "Error deleting volunteer: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Volunteer</title>

    <!-- 
    - favicon
  -->
    <link rel="shortcut icon" href="./assets/images/favicon.png" type="image/svg+xml">

    <!-- 
    - custom css link
  -->
    <link rel="stylesheet" href="./assets/css/admin-volunteer.css">

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

    <?php include "admin-header.php"; ?>
    <main>
        <article>

            <!-- 
        - #PROJECT
      -->

            <section class="section project" aria-labelledby="project-label">
                <div class="container">

                    <h2 class="h2 section-subtitle" id="project-label">Volunteer</h2>

                    <p class="section-text">
                        Dengan menambahkan kegiatan volunteer, kita dapat membantu mewujudkan masyarakat yang lebih
                        baik.
                    </p>

                    <div action="" class="title-wrapper">
                        <button type="button" class="btn btn-primary"
                            onclick="window.location.href='admin-add-volunteer.php'" style="margin-right: 5%">Add
                            Volunteer</button>
                        <button type="button" class="btn btn-primary"
                            onclick="window.location.href='admin-tampilan-volunteer.php'" style="margin-left: 5%">Edit
                            Volunteer</button>
                    </div>

                    <ul class="grid-list">

                        <?php
                        $count = 0;
                        if ($volunteer_result->num_rows > 0):
                            while ($row = $volunteer_result->fetch_assoc()):
                                ?>

                                <li style="flex: 0 0 calc(25% - 20px); margin-bottom: 30px">
                                    <div class="project-card">
                                        <button class="delete-btn" data-volunteer-id="<?php echo $row['id_volunteer']; ?>"
                                            style="color: red">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </button>

                                        <figure class="card-banner img-holder" style="--width: 560; --height: 350;">
                                            <img src="<?php echo $row['image'] ?>" width="560" height="350" loading="lazy"
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

                                                <a href="detail-volunteer.php?id=<?php echo $row['id_volunteer']; ?>"
                                                    class="read-more-btn">Read More</a>
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
                        $sql2 = "SELECT COUNT(*) AS total FROM volunteer";
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
            </section>

        </article>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const volunteerId = this.getAttribute('data-volunteer-id');
                    if (confirm('Apakah kamu yakin ingin menghapus data volunteer ini?')) {
                        // Make an AJAX request to the backend to delete the volunteer
                        deleteVolunteer(volunteerId);
                    }
                });
            });

            function deleteVolunteer(volunteerId) {
                // Make an AJAX request to the backend PHP script
                const xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Reload the page after successful deletion
                        window.location.reload();
                    }
                };
                xhr.open('GET', 'delete-volunteer.php?id=' + volunteerId, true);
                xhr.send();
            }
        });
    </script>


    <!-- 
    - ionicon link
-->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <?php include "footer.php"; ?>
</body>