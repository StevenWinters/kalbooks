<?php
session_start();
include 'database.php';
include 'navbar.php';

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    $sql = "SELECT * FROM history WHERE user_id = '{$_SESSION['user_id']}'";
    $results = mysqli_query($conn, $sql);
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kalbooks | History</title>
        <link rel="stylesheet" href="index.css">
    </head>

    <body>
        <section class="block container--lg">
            <?php if (isset($_GET['error'])) { ?>
                <div class="flex justify--center align--center error">
                    <p><i class="fa-solid fa-triangle-exclamation icon--warning"></i><?php echo $_GET['error'] ?></p>
                </div>
            <?php } ?>
            <header>
                <h1>History</h1>
            </header>
            <div class="table__container">
                <table>
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Status</th>
                            <th>Cancel Reservation</th>
                        </tr>
                    </thead>
                    <?php
                    if (mysqli_num_rows($results) > 0) {
                    ?>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($results)) {
                            ?>
                                <tr>
                                    <td><?php echo $row['book_id'] ?></td>
                                    <td><?php echo $row['status'] ?></td>
                                    <td>
                                        <form action="cancelReservation.php" method="post">
                                            <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                            <button type="submit" class="btn btn--primary">Cancel Reservation</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                    <?php
                    } else {
                    ?>
                        <div class="history__error">
                            <p>You have not reserved/borrowed any books yet.</p>
                        </div>
                    <?php
                    } ?>
                </table>
            </div>
            <div class="flex justify--center history__container"><a href="index.php"><button class="btn btn--primary btn--lg">Back to Home</button></a></div>
        </section>
    </body>

    </html>

<?php
} else {
    header('Location: login.php');
}

// RESERVE BOOK ID
// INITIAL STATUS RESERVE
// SHOW MESSAGE IF NO HISTORY
// SELECT RESERVED BOOKS WITH MATCHING USER ID