<?php
session_start();
include 'database.php';
include 'navbar.php';

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kalboks | Confirm Reserve</title>
        <link rel="stylesheet" href="index.css">
    </head>

    <body>
        <section class="block container--lg flex flex--column justify--center align--center confirm__reserve">
            <header class="confirm__header">
                <h1>Would you like to confirm your reservation?</h1>
            </header>
            <div class="flex flex--column gap--md confirm__btns">
                <form action="reserveConfirmed.php" method="post">
                    <button type="submit" class="btn btn--primary btn--lg btn--full">Confirm</button>
                </form>
                <a href="reserveBooks.php"><button type="submit" class="btn btn--primary btn--lg btn--full">Cancel</button></a>
            </div>
        </section>
    </body>

    </html>
<?php
} else {
    header('Location: login.php');
}
?>