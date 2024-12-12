<?php
session_start();
include 'database.php';

if ($_SESSION['user_type'] == 3) {
    $status = $_POST['status'];
    $historyID = $_POST['history_id'];
    $bookID = $_POST['book_id'];
    $userID = $_POST['user_id'];
    $userType = $_POST['user_type'];

    if ($status === 'BORROWED' && isset($_POST['user_id']) && isset($_POST['user_type'])) {
        $date = new DateTime();
        $dateOfBorrow = $date->format('Y-m-d');

        if ($userType == 1) {
            $dateOfReturn = $date->modify('+7 days')->format('Y-m-d');
        } else if ($userType == 2 || $userType == 3) {
            $dateOfReturn = $date->modify('-16 days')->format('Y-m-d');
        }

        $updateReserveSQL = "UPDATE history
                             SET status = '{$status}'
                             WHERE history_id = {$historyID}";

        if (mysqli_query($conn, $updateReserveSQL)) {
            $insertSQL = "INSERT INTO borrow (user_id, book_id, date_of_borrow, date_of_return)
                          VALUES ($userID, $bookID, '$dateOfBorrow', '$dateOfReturn')";
            mysqli_query($conn, $insertSQL);
            header("Location: penaltyOnExpiredBorrow.php?user-id={$userID}&user-type={$userType}&book-id={$bookID}");
        } else {
            echo "Error inserting borrow record: " . mysqli_error($conn);
        }
    } elseif ($status === 'DELETE') {
        $deleteSQL = "DELETE FROM history WHERE history_id = {$historyID}";
        if (mysqli_query($conn, $deleteSQL)) {
            $updateAvailabilitySQL = "UPDATE books
                                      SET is_available = true
                                      WHERE book_id = $bookID";
            mysqli_query($conn, $updateAvailabilitySQL);
        }
        header("Location: adminHistory.php");
    } else if ($status === 'RETURNED') {
        $updateReturnSQL = "UPDATE history
                            SET status = '{$status}'
                            WHERE history_id = {$historyID}";
        if (mysqli_query($conn, $updateReturnSQL)) {
            $updateAvailabilitySQL = "UPDATE books
                                      SET is_available = true
                                      WHERE book_id = $bookID";
            if (mysqli_query($conn, $updateAvailabilitySQL)) {
                $date = new DateTime();

                $returnDate = $date->format('Y-m-d');

                $insertReturnSQL = "INSERT INTO `return`
                                    (user_id, return_date, book_id)
                                    VALUES
                                    ($userID, '$returnDate', $bookID)";
                mysqli_query($conn, $insertReturnSQL);
            }
            header('Location: adminHistory.php');
        }
    } else {
        header('Location: index.php?error=incorrect status');
    }
} else {
    header('Location: index.php?error=unauthorized access');
}
