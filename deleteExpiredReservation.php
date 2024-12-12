<?php

include 'database.php';

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    $currentDate = date('Y-m-d');

    $deleteSQL = "DELETE FROM history WHERE reserve_deadline < '$currentDate'";

    if (mysqli_query($conn, $deleteSQL)) {
        echo "";
    } else {
        echo "Error deleting reservations: " . mysqli_error($conn);
    }
} else {
    echo 'Session not set.';
}
