<?php
session_start();

if (isset($_POST['book_id']) && isset($_SESSION['books'])) {
    $bookId = $_POST['book_id'];

    foreach ($_SESSION['books'] as $index => $book) {
        if ($book['book_id'] == $bookId) {
            unset($_SESSION['books'][$index]);
            $_SESSION['books'] = array_values($_SESSION['books']);
            break;
        }
    }
}

header('Location: reserveBooks.php');
exit();
