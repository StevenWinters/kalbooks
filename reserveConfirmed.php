<?php
session_start();
include 'database.php';
include 'deleteExpiredReservation.php';
include 'navbar.php';

if (isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['books'])) {
    foreach ($_SESSION['books'] as $book) {
        $date = new DateTime();

        $userID = $_SESSION['user_id'];
        $bookID =  $book['book_id'];
        $dateOfReserve = $date->format('Y-m-d');
        $reserveDeadline = $date->modify('+2 day')->format('Y-m-d');

        $sql = "INSERT INTO history (user_id, book_id, date_of_reserve, reserve_deadline)
                VALUES 
                ('$userID', '$bookID', '$dateOfReserve', '$reserveDeadline')";
        if (mysqli_query($conn, $sql)) {
            $updateAvailabilitySQL = "UPDATE books
                                          SET is_available = false
                                          WHERE book_id = $bookID";
            mysqli_query($conn, $updateAvailabilitySQL);
        }
        $_SESSION['books'] = [];
    }
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kalbooks | Reserve Confirmed</title>
        <link rel="stylesheet" href="index.css">
    </head>

    <body>
        <section class="block container--lg flex flex--column justify--center align--center confirm__reserve">
            <h1>Confirmed.</h1>
            <a href="index.php"><button class="btn btn--primary btn--lg confirmed__btn">Back to Home</button></a>
        </section>
    </body>

    </html>
<?php
} else {
    header('Location: login.php');
}
?>