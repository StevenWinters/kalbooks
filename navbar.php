<?php


if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    $username = $_SESSION['username'];
    $abbreviation = substr($username, 0, 1);
    $userTypeId =  $_SESSION['user_type'];
    $userType = "";
    switch ($userTypeId) {
        case 2:
            $userType = 'Teacher';
            break;
        case 3:
            $userType = 'Admin';
            break;
        default:
            $userType = 'Student';
            break;
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="assets/kalbooks-logo.png">
        <script src="index.js" defer></script>
        <script src="https://kit.fontawesome.com/8c311b7330.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <header>
            <nav class="nav__bar">
                <div class="flex justify--between align--center container">
                    <div class="flex flex--column justify--center align--center hamburger__menu" onclick="openSidebar()">
                        <span class="hamburger__bar"></span>
                        <span class="hamburger__bar"></span>
                        <span class="hamburger__bar"></span>
                    </div>
                    <ul class="flex nav__lists">
                        <li class="nav__list"><a href="searchResults.php" class="link nav__link">Search</a></li>
                        <li class="nav__list"><a href="reserveBooks.php" class="link nav__link">Reserve</a></li>
                        <?php if ($_SESSION['user_type'] == 3) {
                            echo '<li class="nav__list"><a href="adminHistory.php" class="link nav__link">Admin History</a></li>
                             <li class="nav__list"><a href="adminReturn.php" class="link nav__link">Return History</a></li>';
                        } else {
                            echo '<li class="nav__list"><a href="history.php" class="link nav__link">History</a></li>';
                        } ?>
                    </ul>
                    <a href="index.php" class="flex justify--center align--center nav__logo">
                        <img
                            src="assets/kalbooks-logo.png"
                            class="img img--logo"
                            alt="">
                        <span class="logo__heading">K.A.L Books</span>
                    </a>
                    <div class="flex justify--center align--center account__profile" onclick="showAccount()">
                        <span><?php echo $abbreviation ?></span>
                    </div>
                    <div class="flex flex--column justify--between account__details">
                        <div>
                            <span class="flex justify--center align--center icon icon--close account__close" onclick="closeAccount()"><i class="fa-solid fa-xmark"></i></span>
                            <div class="flex gap--md align--center account__user">
                                <div class="flex justify--center align--center detail__profile"><?php echo $abbreviation ?></div>
                                <span class="account__name"><?php echo $_SESSION['full_name'] ?></span>
                            </div>
                            <div class="account__detail"><span class="account__type">User Type: <?php echo $userType ?></span></div>
                            <div class="account__detail"><span class="account__id">User ID: <?php echo $_SESSION['user_id'] ?></span></div>
                        </div>
                        <div class="account__detail">
                            <a href="logout.php">
                                <button class="btn btn--primary logout__btn">Log Out</button></a>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <div class="overlay"></div>
        <div class="sidebar">
            <div class="sidebar__container">
                <a href="index.php"><img class="img img--logo" src="assets/kalbooks-logo.png" alt=""></a>
                <i class="fa-solid fa-circle-xmark icon icon--close sidebar__close" onclick="closeSidebar()"></i>
            </div>
            <ul class="sidebar__lists">
                <li class="sidebar__list"><a href="index.php" class="link sidebar__link"><i class="fa-solid fa-house sidebar__icon"></i><span>Home</span></a></li>
                <li class="sidebar__list"><a href="reserveBooks.php" class="link sidebar__link"><i class="fa-solid fa-clipboard-list sidebar__icon"></i>Reserve</a></li>
                <?php if ($_SESSION['user_type'] == 3) {
                    echo '<li class="sidebar__list"><a href="adminHistory.php" class="link sidebar__link"><i class="fa-solid fa-clock-rotate-left sidebar__icon"></i>Admin History</a></li>';
                    echo '<li class="sidebar__list"><a href="adminReturn.php" class="link sidebar__link"><i class="fa-solid fa-right-left sidebar__icon"></i>Return History</a></li>';
                } else {
                    echo '<li class="sidebar__list"><a href="history.php" class="link sidebar__link"><i class="fa-solid fa-clock-rotate-left sidebar__icon"></i>History</a></li>';
                } ?>
                <li class="sidebar__list"><a href="searchResults.php" class="link sidebar__link"><i class="fa-solid fa-magnifying-glass sidebar__icon"></i>Search</a></li>
            </ul>
        </div>
    </body>

    </html>

<?php
} else {
    header('Location: login.php');
}
?>