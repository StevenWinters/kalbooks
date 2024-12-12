<?php

session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $feedback = $_POST['feedback'];
    $userId = $_SESSION['user_id'];

    if (!$feedback) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    $feedbackSQL = "INSERT INTO feedback (user_id, comment)
            VALUES ($userId, '$feedback')";

    try {
        mysqli_query($conn, $feedbackSQL);
        $status = true;
    } catch (mysqli_sql_exception) {
        $status = false;
    }

    // $currentPage = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    // if ($_SERVER['HTTP_REFERER'] === $currentPage)
    //     header('Location: index.php');
    // 
} else {
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalbooks | Feedback</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://kit.fontawesome.com/8c311b7330.js" crossorigin="anonymous"></script>
</head>

<body>
    <section class="flex flex--column justify--center align--center block container feedback">
        <div class="feedback__container">
            <?php if ($status) { ?>
                <i class="fa-solid fa-circle-check feedback__icon feedback--success"></i>
                <h1>Your feedback has been sent.</h1>
                <p>We gladly appreciate it.</p>
            <?php } else { ?>
                <i class="fa-solid fa-circle-exclamation feedback__icon feedback--error"></i>
                <h1>Feedback was not sent</h1>
                <p>There may be an error in the system. Please try again.</p>
            <?php } ?>

            <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>"><button class="btn btn--primary btn--lg">Return</button></a>
        </div>
    </section>
</body>

</html>