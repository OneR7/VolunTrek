<?php
require_once "connection.php";

// Fetch names from the database
$sql = "SELECT nama FROM user";
$result = mysqli_query($conn, $sql);

// Check if there are any names
if ($result) {
    // Initialize an array to store names
    $a = array();

    // Fetch each name and store it in the $a array
    while ($row = mysqli_fetch_assoc($result)) {
        $a[] = $row['nama'];
    }

    // Free the result set
    mysqli_free_result($result);

    // Close the database connection
    mysqli_close($conn);
} else {
    // Handle the error if the query fails
    echo "<div class='alert alert-danger'>Error fetching names</div>";
    exit();
}

// get the q parameter from URL
$q = $_REQUEST["q"];

$hint = "";

// lookup all hints from array if $q is different from ""
// Assume $q is the username to be checked, and $a is an array of existing usernames

if ($q !== "") {
    $q = strtolower($q);
    $isUsernameUsed = false;

    foreach ($a as $name) {
        if (strtolower($name) === $q) {
            // Username is already used
            $isUsernameUsed = true;
            break; // No need to continue checking
        }
    }

    if ($isUsernameUsed) {
        echo "Username telah digunakan";
    } else {
        echo "Username tersedia";
    }
} else {
    echo "Username tidak boleh kosong";
}


// Output "no suggestion" if no hint was found or output correct values
;
?>