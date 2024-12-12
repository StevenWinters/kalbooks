<?php

session_start();
include 'database.php';

$userID = $_SESSION['user_id'];
$bookID = $_POST['book_id'];

$sql = "DELETE FROM history WHERE user_id = $userID AND book_id = $bookID";

if (mysqli_query($conn, $sql)) {
    $updateAvailabilitySQL = "UPDATE books
           SET is_available = true
           WHERE book_id = $bookID";
    mysqli_query($conn, $updateAvailabilitySQL);
}
header("Location: history.php");
