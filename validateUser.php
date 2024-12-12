<?php

include 'database.php';

session_start();

$userId = $_POST['text'];

$sql = 'SELECT * FROM users';
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $firstName = htmlspecialchars($row['first_name']);

        if (strtolower($firstName) === strtolower($userId)) {
            echo 'User is Available.';
        }
    }
}
