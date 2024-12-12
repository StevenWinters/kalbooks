<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <footer class="block footer__container">
        <div class="container footer__feedback">
            <h3>Leave a feedback</h3>
            <form action="sendFeedback.php" method="post">
                <textarea name="feedback" placeholder="Give a feedback..." rows="3" class="feedback__input"></textarea>
                <button type="submit" class="btn btn--primary btn--lg btn--full feedback__btn">Submit</button>
            </form>
        </div>
        <div class="grid grid--1x2 grid--center gap--lg align--center container--lg">
            <div class="flex align--center footer__links">
                <i class="fa-solid fa-user-large footer__user"></i>
                <ul>
                    <li class="footer__list"><a href="index.php" class="link footer__link">Home</a></li>
                    <li class="footer__list"><a href="search.php" class="link footer__link">Search</a></li>
                    <li class="footer__list"><a href="reserveBooks.php" class="link footer__link">Reserve</a></li>
                    <?php if ($_SESSION['user_type'] == 3) {
                        echo '<li class="footer__list"><a href="history.php" class="link footer__link">Admin History</a></li>';
                        echo '<li class="footer__list"><a href="history.php" class="link footer__link">Return History</a></li>';
                    } else {
                        echo '<li class="footer__list"><a href="history.php" class="link footer__link">History</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <div>
                <div class="flex gap--lg align--center footer__logo">
                    <img src="assets/kalbooks-logo.png" class="img footer__img" alt="">
                    <span class="footer__heading">K.A.L. Books</span>
                </div>
                <p class="footer__copyright">Copyright &copy; 2024. Powered by the 12 - Integrity PWU ICT.</p>
            </div>
        </div>
    </footer>
</body>

</html>