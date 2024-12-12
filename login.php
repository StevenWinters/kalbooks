<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalbooks | Log In</title>
    <link rel="stylesheet" href="index.css">
    <link rel="icon" href="assets/kalbooks-logo.png">
    <script src="index.js"></script>
    <script src="https://kit.fontawesome.com/8c311b7330.js" crossorigin="anonymous"></script>
</head>

<body>
    <section class="block flex justify--center align--center login">
        <div class="container">
            <form action="loginFunction.php" method="post" class="flex flex--column login__form">
                <img src="assets/kalbooks-logo.png" class="img login__logo" alt="">
                <header class="flex flex--column justify--center align--center gap--sm login__header">
                    <h1>Log into K.A.L. Books</h1>
                </header>
                <div class="flex flex--column form__group">
                    <input
                        type="text"
                        class="input login__input"
                        name="userId" id=" user__id"
                        placeholder="User ID" />
                    <p class="login__info">ex: 11</p>
                </div>
                <div class="flex flex--column form__group">
                    <input
                        type="password"
                        class="input login__input"
                        name="password" id="password"
                        placeholder="Password" />
                </div>
                <?php
                if (isset($_GET['error'])) {
                ?>
                    <div class="error">
                        <p><i class="fa-solid fa-triangle-exclamation icon--warning"></i><?php echo $_GET['error'] ?></p>
                    </div>
                <?php
                }
                ?>
                <input
                    type="submit"
                    class="btn btn--primary login__submit"
                    value="Log in">
            </form>
        </div>
    </section>
</body>

</html>

<?php



session_start();
