<?php
session_start();
include 'database.php';

if ($_SESSION['user_type'] == 3) {
    include 'navbar.php';

    $shortenedSQL = "SELECT * 
                     FROM history r
                     JOIN users u ON r.user_id = u.user_id
                     JOIN books b ON r.book_id = b.book_id";

    if (isset($_GET['user-id'])) {
        $sql = "$shortenedSQL WHERE r.user_id = {$_GET['user-id']}";
    } else {
        $sql = "$shortenedSQL";
    }

    $results = mysqli_query($conn, $sql);
    $hasNonReturnedRecords = false;

    while ($row = mysqli_fetch_assoc($results)) {
        if ($row['status'] !== 'RETURNED') {
            $hasNonReturnedRecords = true;
            break;
        }
    }

    mysqli_data_seek($results, 0);
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kalbooks | Admin History</title>
        <link rel="stylesheet" href="index.css">
    </head>

    <body>
        <section class="block container--lg">
            <?php if (isset($_GET['error'])) { ?>
                <div class="flex justify--center align--center error">
                    <p><i class="fa-solid fa-triangle-exclamation icon--warning"></i><?php echo $_GET['error'] ?></p>
                </div>
            <?php } ?>
            <?php if (isset($_GET['warning'])) { ?>
                <div class="flex justify--center align--center error">
                    <p><i class="fa-solid fa-triangle-exclamation icon--warning"></i><?php echo $_GET['warning'] ?></p>
                </div>
            <?php } ?>
            <?php if ($hasNonReturnedRecords) { ?>
                <header class="table__header">
                    <h1>Admin History</h1>
                    <div class="flex gap--sm">
                        <form action="adminFilterHistory.php" method="post" class="table__clear">
                            <button type="submit" class="btn btn--primary">Clear</button>
                        </form>
                        <form action="adminFilterHistory.php" method="post" class="flex justify--between align--center table__field">
                            <label for="user__id"><i class="fa-solid fa-magnifying-glass table__icon"></i></label>
                            <input type="number" name="user_id" id="user__id" placeholder="Enter user ID" class="input table__input">
                            <button type="submit" class="btn btn--primary">Filter</button>
                        </form>
                    </div>
                </header>
                <div class="table__container">
                    <table>
                        <thead>
                            <tr>
                                <th>History ID</th>
                                <th>User ID</th>
                                <th>Full Name</th>
                                <th>Book ID</th>
                                <th>Book Title</th>
                                <th>Date of Reserve</th>
                                <th>Reserve Deadline</th>
                                <th>Status</th>
                                <th>Penalty</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($results)) {
                                if ($row['status'] !== 'RETURNED') {
                                    $fullName = "{$row['first_name']} {$row['last_name']}";
                                    $penaltyAmount = 'none';
                                    $borrowSQL = "SELECT borrow_id FROM borrow WHERE user_id = {$row['user_id']} AND book_id = {$row['book_id']}";
                                    $borrowResult = mysqli_query($conn, $borrowSQL);
                                    if (mysqli_num_rows($borrowResult) > 0) {
                                        $borrowRow = mysqli_fetch_assoc($borrowResult);
                                        $penaltySQL = "SELECT amount FROM penalty WHERE borrow_id = {$borrowRow['borrow_id']}";
                                        $penaltyResult = mysqli_query($conn, $penaltySQL);
                                        if (mysqli_num_rows($penaltyResult) > 0) {
                                            $penaltyRow = mysqli_fetch_assoc($penaltyResult);
                                            $penaltyAmount = $penaltyRow['amount'];
                                        }
                                    }
                            ?>
                                    <tr>
                                        <td><?php echo $row['history_id'] ?></td>
                                        <td><?php echo $row['user_id'] ?></td>
                                        <td><?php echo $fullName ?></td>
                                        <td><?php echo $row['book_id'] ?></td>
                                        <td><?php echo $row['title'] ?></td>
                                        <td><?php echo $row['date_of_reserve'] ?></td>
                                        <td><?php echo $row['reserve_deadline'] ?></td>
                                        <td><?php echo $row['status'] ?></td>
                                        <td><?php echo $penaltyAmount ?></td>
                                        <td>
                                            <?php if ($row['status'] !== "RETURNED") { ?>
                                                <form action="updateReservationStatus.php" method="POST">
                                                    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                                    <input type="hidden" name="user_type" value="<?php echo $row['user_type_id']; ?>">
                                                    <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                                    <input type="hidden" name="history_id" value="<?php echo $row['history_id']; ?>">
                                                    <input type="hidden" name="status" value="BORROWED">
                                                    <?php if ($row['status'] !== 'BORROWED') { ?>
                                                        <button type="submit" class="btn btn--accent">Borrow</button>
                                                    <?php } ?>
                                                </form>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <form action="updateReservationStatus.php" method="POST">
                                                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                                <input type="hidden" name="user_type" value="<?php echo $row['user_type_id']; ?>">
                                                <input type="hidden" name="history_id" value="<?php echo $row['history_id']; ?>">
                                                <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                                <input type="hidden" name="status" value="DELETE">
                                                <button type="submit" class="btn btn--primary">Delete</button>
                                            </form>
                                        </td>
                                        <td>
                                            <?php if ($row['status'] === 'BORROWED') { ?>
                                                <form action="updateReservationStatus.php" method="POST">
                                                    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                                    <input type="hidden" name="history_id" value="<?php echo $row['history_id']; ?>">
                                                    <input type="hidden" name="status" value="RETURNED">
                                                    <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                                    <button type="submit" class="btn btn--secondary">Return</button>
                                                </form>
                                            <?php } ?>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <p>No records found with BORROWED or RESERVED status.</p>
            <?php } ?>
        </section>
    </body>

    </html>

<?php
} else {
    header("Location: index.php");
}
?>