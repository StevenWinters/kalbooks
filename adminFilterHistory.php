<?php

include 'database.php';

if (isset($_POST['user_id'])) {
    $userID = $_POST['user_id'];

    if ($userID) {
        $sql = "SELECT *
        FROM history
        WHERE user_id = $userID";
        $results = mysqli_query($conn, $sql);

        if (mysqli_num_rows($results) > 0) {
            while ($row = mysqli_fetch_assoc($results)) {
                header("Location: adminHistory.php?user-id={$row['user_id']}");
            }
        } else {
            header("Location: adminHistory.php?error= No matching record found.");
        }
    } else {
        header("Location: adminHistory.php?error=Please enter user id.");
    }
} else {
    header("Location: adminHistory.php");
}
