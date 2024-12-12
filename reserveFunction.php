<?php
session_start();
include 'database.php';

$bookId = $_POST['bookId'];

$reservationSQL = "SELECT COUNT(*) AS total_reserved
                   FROM history 
                   WHERE user_id = '{$_SESSION['user_id']}' AND status IN ('RESERVED', 'BORROWED')";
$reservationResults = mysqli_query($conn, $reservationSQL);
$reservationCount = mysqli_fetch_assoc($reservationResults)['total_reserved'];

$maxLimit = ($_SESSION['user_type'] == 1) ? 3 : 5;

if ($reservationCount + (isset($_SESSION['books']) ? count($_SESSION['books']) : 0) < $maxLimit) {
    $joinSQL = "SELECT b.title, 
    b.is_available,
    b.book_id,
    b.copyright,
    g.name AS genre_name, 
    a.name AS author_name, 
    p.name AS publisher_name
FROM books b
JOIN genre g ON b.genre_id = g.genre_id
JOIN book_authors ba ON ba.book_id = b.book_id
JOIN authors a ON ba.author_id = a.author_id
JOIN book_publishers bp ON bp.book_id = b.book_id
JOIN publishers p ON bp.publisher_id = p.publisher_id
WHERE b.book_id = '$bookId'";

    $results = mysqli_query($conn, $joinSQL);

    if (mysqli_num_rows($results) > 0) {
        $row = mysqli_fetch_assoc($results);

        if (!isset($_SESSION['books'])) {
            $_SESSION['books'] = [];
        }

        if (!in_array($row, $_SESSION['books'])) {
            if ($row['is_available']) {
                $_SESSION['books'][] = $row;
            } else {
                header('Location: reserveBooks.php?error=Book is not available.');
                exit();
            }
        }

        header('Location: reserveBooks.php');
    } else {
        header('Location: reserveBooks.php?error=Book not found.');
    }
} else {
    header('Location: reserveBooks.php?error=Reserve limit reached.');
}
