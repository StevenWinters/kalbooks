<?php
session_start();
include 'navbar.php';

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kalbooks | Home</title>
        <link rel="stylesheet" href="index.css">
        <script src="index.js" defer></script>
    </head>

    <body>
        <section>
            <div class="overlay"></div>
            <div class="search__overlay">
                <img
                    src="assets/pwu-manila.jpg"
                    class="img search__img" alt="">
            </div>
            <div class="block container flex flex--column align--center search__field">
                <h1 class="search__heading">Kalbooks.</h1>
                <div onclick="displaySearch()" class="search__input">Search a book...</div>
                <form
                    method="post"
                    action="searchResults.php"
                    onsubmit="searchBook()"
                    class="search__container">
                    <i class="fa-solid fa-xmark icon icon--close search__close"
                        onclick="closeSearch()"></i>
                    <div class="search__box">
                        <input
                            type="text"
                            name="text"
                            id="text"
                            placeholder="Search book..."
                            onkeyup="searchBook()"
                            class="input container__input" />
                    </div>
                    <div class="flex justify--around align--center search__box">
                        <label for="filter" class="search__label">Sort By:</label>
                        <select
                            id="filter"
                            name="filter"
                            class="input container__select"
                            onchange="searchBook()">
                            <option value="title">Title</option>
                            <option value="book_id">Book Id</option>
                            <option value="author">Author</option>
                            <option value="genre">Genre</option>
                            <option value="publisher">Publisher</option>
                        </select>
                    </div>
                    <div id="results" class="autosearch__results"></div>
                    <button type="submit" class="btn btn--primary search__btn">Search book</button>
                </form>
                <span class="justify--center align--center outer__bulb">
                    <span class="justify--center align--center inner__bulb">
                        <i class="fa-solid fa-lightbulb icon--bulb"></i>
                    </span>
                </span>
            </div>
        </section>
        <section class="block__hero container--lg">
            <div class="grid hero__grid align--center hero">
                <article class="hero__search">
                    <h2 class="text--light">What's on your mind, <?php echo $_SESSION['username'] ?>?</h2>
                    <img class="img hero__img" src="assets/search.jpg" alt="">
                    <div class="search__content">
                        <h3>Search</h3>
                        <p>Quickly find books, articles, or other library resources by keyword, title, author, and more!</p>
                        <a href="searchResults.php"><button class="btn btn--primary btn--lg">View</button></a>
                    </div>
                </article>
                <article class="grid grid--1x2 gap--md align--center hero__reserve hero__container">
                    <img class="img hero__img" src="assets/reserve.jpg" alt="">
                    <div class="hero__content">
                        <h3>Reserve</h3>
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Officiis, eligendi?</p>
                        <a href="reserveBooks.php"><button class="btn btn--primary">View</button></a>
                    </div>
                </article>
                <article class="grid grid--1x2 gap--md align--center hero__container">
                    <img class="img hero__img" src="assets/history.jpg" alt="">
                    <div class="hero__content">
                        <h3>History</h3>
                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Rem, repudiandae.</p>
                        <a href="history.php"><button class="btn btn--primary">View</button></a>
                    </div>
                </article>
            </div>
        </section>
        <section class="block container">
            <h2>The Library</h2>
            <div class="slider__wrapper">
                <button id="prev__slide" class="slide__btn">&lt;</button>
                <div class="image__list">
                    <img src="assets/library-1.jpg" class="img image__item" alt="">
                    <img src="assets/library-2.jpg" class="img image__item" alt="">
                    <img src="assets/library-3.jpg" class="img image__item" alt="">
                </div>
                <button id="next__slide" class="slide__btn">&gt;</button>
            </div>
            <div class="slider__scrollbar">
                <div class="scrollbar__track">
                    <div class="scrollbar__thumb"></div>
                </div>
            </div>
        </section>
        <section class="block about">
            <div class="container--lg grid grid--1x2 gap--md grid--center about__container">
                <div class="about__order">
                    <div class="about__content">
                        <span class="university__heading">Philippine Women's University</span>
                        <h3>What is Kalbooks?</h3>
                        <p>Welcome to K.A.L Books, your ultimate school library database designed to enhance your reading experience! With an extensive collection of books, e-books, articles, and multimedia resources, finding what you need is effortless.</p>
                        <img src="assets/kalbooks-logo.png" class="img img--logo about__logo" alt="">
                    </div>
                    <div class="flex justify--center align--center">
                        <div>
                            <div class="text--lg">Your schools's</div>
                            <div class="text--xlg">smart</div>
                            <div class="text--lg">library solution</div>
                        </div>
                    </div>
                </div>
                <div class="flex flex--column align--center about__order">
                    <span class="flex justify--center align--center about__icon"><i class="fa-solid fa-question"></i></span>
                    <h2 class="about__heading">Who are we?</h2>
                    <img src="assets/who-are-we.jpg" class="img" alt="">
                </div>
            </div>
            <section class="block__FAQ container--lg FAQ">
                <h2>Frequently Asked Questions</h2>
                <div class="flex justify--between align--center FAQ__container" onclick="showFAQDesc(this)">
                    <p>How does reserving work?</p>
                    <i class="fa-solid fa-caret-down icon FAQ__icon"></i>
                </div>
                <div class="FAQ__description" id="FAQ-1">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium, aut suscipit est iure quisquam quo voluptatibus reprehenderit aliquid hic ut!</p>
                </div>
                <div class="flex justify--between align--center FAQ__container" onclick="showFAQDesc(this)">
                    <p>When will my reservation be accepted?</p>
                    <i class="fa-solid fa-caret-down icon FAQ__icon"></i>
                </div>
                <div class="FAQ__description" id="FAQ-2">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium, aut suscipit est iure quisquam quo voluptatibus reprehenderit aliquid hic ut!</p>
                </div>
                <div class="flex justify--between align--center FAQ__container" onclick="showFAQDesc(this)">
                    <p>Where can I get my book?</p>
                    <i class="fa-solid fa-caret-down icon FAQ__icon"></i>
                </div>
                <div class="FAQ__description" id="FAQ-3">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium, aut suscipit est iure quisquam quo voluptatibus reprehenderit aliquid hic ut!</p>
                </div>
            </section>
        </section>
        <section class="block block__dark showcase">
            <div class="container">
                <header class="justify--between align--center showcase__header">
                    <h2>With over 2000+ books</h2>
                    <a href="searchResults.php"><button class="btn btn--primary btn--lg">View</button></a>
                </header>
            </div>
            <div class="container--lg">
                <div class="grid grid--center gap--sm showcase__grid showcase__list">
                    <img src="assets/book-2.jpg" class="img showcase__img" alt="">
                    <img src="assets/book-1.jpg" class="img showcase__img" alt="">
                    <img src="assets/book-3.jpg" class="img showcase__img" alt="">
                </div>
            </div>
        </section>

    </body>

    </html>

<?php
    include 'footer.php';
} else {
    header('Location: login.php');
}
?>