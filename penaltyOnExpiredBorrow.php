<?php

include 'database.php';

session_start();

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
        $userID = $_GET['user-id'];
        $bookID = $_GET['book-id'];
        $userType = $_GET['user-type'];
        $sql = "SELECT borrow_id, date_of_return
            FROM borrow
            WHERE user_id = $userID
            AND book_id = $bookID";
        $results = mysqli_query($conn, $sql);
        if (mysqli_num_rows($results) > 0) {
                while ($row = mysqli_fetch_assoc($results)) {
                        $date = new DateTime();
                        $currentDate = $date->format('Y-m-d');
                        $dateOfReturn = new DateTime($row['date_of_return']);
                        if ($dateOfReturn < $date) {
                                $daysOverdue = $dateOfReturn->diff($date)->days;
                                $borrowID = $row['borrow_id'];

                                $penaltyDays = 0;
                                $currentDate = clone $dateOfReturn;
                                while ($currentDate <= $date) {
                                        if ($currentDate->format('N') < 6) { // Monday to Friday
                                                $penaltyDays++;
                                        }
                                        $currentDate->modify('+1 day');
                                }

                                $basePenalty = ($userType == 1) ? 2 : 1;
                                $totalPenalty = $penaltyDays * $basePenalty;

                                $checkPenaltySQL = "SELECT amount FROM penalty WHERE borrow_id = $borrowID";
                                $penaltyResult = mysqli_query($conn, $checkPenaltySQL);

                                if (mysqli_num_rows($penaltyResult) > 0) {
                                        $updatePenaltySQL = "UPDATE penalty
                                         SET amount = $totalPenalty
                                         WHERE borrow_id = $borrowID";
                                        mysqli_query($conn, $updatePenaltySQL);
                                } else {
                                        $insertPenaltySQL = "INSERT INTO penalty (borrow_id, amount) 
                                         VALUES ($borrowID, $totalPenalty)";
                                        mysqli_query($conn, $insertPenaltySQL);
                                }
                                header("Location: adminHistory.php?warning=User ID: $userID's penalty has been increased to $totalPenalty");
                        } else {
                                header("Location: adminHistory.php");
                        }
                }
        } else {
                echo 'No borrow record found.';
        }
} else {
        echo 'Unauthorized Access.';
}
