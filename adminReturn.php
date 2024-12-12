<?php

session_start();
include 'database.php';

if ($_SESSION['user_type'] == 3) {
    include 'navbar.php';

    $shortenedSQL = "SELECT * 
        FROM history r
        JOIN users u
            ON r.user_id = u.user_id
        JOIN books b
            ON r.book_id = b.book_id";
    if (isset($_GET['user-id'])) {
        $sql = "$shortenedSQL
        WHERE r.user_id = {$_GET['user-id']}";
    } else {
        $sql = "$shortenedSQL";
    }

    $results = mysqli_query($conn, $sql);
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kalbooks | Admin Return History</title>
        <link rel="stylesheet" href="index.css">
    </head>

    <body>
        <section class="block container--lg">
            <?php if (isset($_GET['error'])) {
            ?>
                <div class="flex justify--center align--center error">
                    <p><i class="fa-solid fa-triangle-exclamation icon--warning"></i><?php echo $_GET['error'] ?></p>
                </div>
            <?php
            } ?>
            <header class="table__header">
                <h1>Returned History</h1>
                <div class="flex gap--sm">
                    <form action="adminFilterReturn.php" method="post" class="table__clear">
                        <button type="submit" class="btn btn--primary">Clear</button>
                    </form>
                    <form action="adminFilterReturn.php" method="post" class="flex justify--between align--center table__field">
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
                        </tr>
                    </thead>
                    <?php
                    if (mysqli_num_rows($results) > 0) { ?>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_assoc($results)) {
                                $fullName = "{$row['first_name']} {$row['last_name']}";
                                if ($row['status'] === 'RETURNED') {
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
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    <?php
                    } ?>
                </table>
            </div>
        </section>
    </body>

    </html>

<?php

} else {
    header("Location: index.php");
}
