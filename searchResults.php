<?php
session_start();
include 'database.php';
include 'navbar.php';

$bookId = $_GET['book_id'] ?? null;

$search = $_POST['text'] ?? ($_GET['text'] ?? '');
$filter = $_POST['filter'] ?? ($_GET['filter'] ?? 'title');

$resultsPerPage = isset($_GET['resultsPerPage']) ? intval($_GET['resultsPerPage']) : 10;
$offset = isset($_GET['page-nr']) ? ($_GET['page-nr'] - 1) * $resultsPerPage : 0;
$pattern = '.*' . mysqli_real_escape_string($conn, $search) . '.*';

$joinSQL = "SELECT b.title, 
                   b.book_id,
                   b.copyright, 
                   b.is_available,
                   g.name AS genre_name, 
                   a.name AS author_name, 
                   p.name AS publisher_name
            FROM books b
            JOIN genre g ON b.genre_id = g.genre_id
            JOIN book_authors ba ON ba.book_id = b.book_id
            JOIN authors a ON ba.author_id = a.author_id
            JOIN book_publishers bp ON bp.book_id = b.book_id
            JOIN publishers p ON bp.publisher_id = p.publisher_id";

if ($bookId) {
    $detailsQuery = "SELECT b.title, 
                            b.book_id, 
                            b.copyright, 
                            b.is_available,
                            g.name AS genre_name, 
                            a.name AS author_name, 
                            p.name AS publisher_name
                     FROM books b
                     JOIN genre g ON b.genre_id = g.genre_id
                     JOIN book_authors ba ON ba.book_id = b.book_id
                     JOIN authors a ON ba.author_id = a.author_id
                     JOIN book_publishers bp ON bp.book_id = b.book_id
                     JOIN publishers p ON bp.publisher_id = p.publisher_id
                     WHERE b.book_id = '$bookId'";
    $bookDetails = mysqli_fetch_assoc(mysqli_query($conn, $detailsQuery));

    $title = htmlspecialchars($bookDetails['title']);
    $authorName = htmlspecialchars($bookDetails['author_name']);
    $genreName = htmlspecialchars($bookDetails['genre_name']);
    $publisherName = htmlspecialchars($bookDetails['publisher_name']);
    $copyright = htmlspecialchars($bookDetails['copyright']);
    $isAvailable = htmlspecialchars($bookDetails['is_available']);
    $titleAbbrev = substr($title, 0, 1);

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showDetails('$title', '$bookId', '$authorName', '$genreName', '$publisherName', '$copyright', '$titleAbbrev', '$isAvailable');
        });
    </script>";
}

switch ($filter) {
    case 'book_id':
        $whereClause = "WHERE b.book_id REGEXP '$pattern'";
        break;
    case 'author':
        $whereClause = "WHERE a.name REGEXP '$pattern'";
        break;
    case 'genre':
        $whereClause = "WHERE g.name REGEXP '$pattern'";
        break;
    case 'publisher':
        $whereClause = "WHERE p.name REGEXP '$pattern'";
        break;
    default:
        $whereClause = "WHERE b.title REGEXP '$pattern'";
        break;
}

$sql = "$joinSQL $whereClause LIMIT $resultsPerPage OFFSET $offset";
$result = mysqli_query($conn, $sql);
$rows = mysqli_num_rows($result);

$totalRowsQuery = "SELECT COUNT(DISTINCT b.book_id) as total FROM books b
                    JOIN genre g ON b.genre_id = g.genre_id
                    JOIN book_authors ba ON ba.book_id = b.book_id
                    JOIN authors a ON ba.author_id = a.author_id
                    JOIN book_publishers bp ON bp.book_id = b.book_id
                    JOIN publishers p ON bp.publisher_id = p.publisher_id 
                    $whereClause";
$totalResult = mysqli_query($conn, $totalRowsQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRows = $totalRow['total'];
$pages = ceil($totalRows / $resultsPerPage);

$searchParam = urlencode($search);
$filterParam = urlencode($filter);
$currentPage = isset($_GET['page-nr']) ? (int)$_GET['page-nr'] : 1;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalbooks | Search Results</title>
    <link rel="stylesheet" href="index.css">
    <script src="https://kit.fontawesome.com/8c311b7330.js" crossorigin="anonymous"></script>
    <script>
        function showDetails(title, bookId, authorName, genreName, publisherName, copyright, titleAbbrev, isAvailable) {
            let status = true;
            if (isAvailable === '0') status = false;


            const pageTab = document.querySelector('.page__tab');
            const pageDetails = document.querySelector('.page__details');
            const overlay = document.querySelector('.overlay');
            pageDetails.innerHTML = `
            <div class="block flex flex--column justify--between detail__overlay">
                <div class="flex gap--md">
                    <div class="flex justify--center align--center book__abbrev">${titleAbbrev}</div>
                    <div class="flex flex--column">
                        <span class="book__id">Book ID: ${bookId}</span>
                        <span class="book__heading">${title}</span>
                        <span class="book__author">${authorName}</span>
                        <div class="flex flex--column book__subs">
                            <span class="book__sub"><span class="book__category">Genre:</span> ${genreName}</span>
                            <span class="book__sub"><span class="book__category">Publisher:</span> ${publisherName}</span>
                            <span class="book__copyright"><span class="book__category">Copyright:</span> ${copyright}</span>
                            <span class="book__status">Availability status: <span class="${status ? 'status--active' : 'status--inactive'}">${status ? 'Available' : 'Not available'}</span></span>
                        </div>
                    </div>
                </div>
                <div class="flex flex--column justify--center gap--sm page__btns">
                <button class="btn btn--primary btn--lg page__btn" onclick="closeDetails()">Return to book list</button>
                <a href="reserveBooks.php?book-id=${bookId}">
                    <button class="btn btn--primary btn--lg page__btn">Proceed to reservation</button>
                </a>
            </div>
            </div>
            
            `;
            pageTab.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeDetails() {
            const pageTab = document.querySelector('.page__tab');
            const overlay = document.querySelector('.overlay');
            overlay.classList.remove('active');
            pageTab.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    </script>
</head>

<body>
    <div class="overlay"></div>
    <div class="block__results container--lg">
        <h1 class="results__heading">Results</h1>
        <div class="flex flex--wrap align--center gap--md results__container">
            <div class="results__filter">
                <div class="flex align--center results__absolute">
                    <i class="fa-solid fa-arrow-left-long results__icons"></i>
                    <a href="index.php" class="link results__link">Back to Home</a>
                </div>
                <button class="btn btn--primary filter__btn" onclick="displaySearch()">Filter</button>
            </div>
            <div onclick="displaySearch()" class="search__input results__input">Search a book...</div>
            <button class="btn btn--primary results__btn" onclick="displaySearch()">Search book</button>
        </div>
        <div class="result__count">
            <p class="result__input">Showing results of <?php echo "\"$searchParam\"" ?></p>
            <p><?php echo $totalRows ?> results found</p>
        </div>
    </div>
    <div class="flex flex--column justify--center align--center">
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
    </div>
    <div class="block container--lg search__results">
        <?php
        if ($rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $title = htmlspecialchars($row['title']);
                $bookId = htmlspecialchars($row['book_id']);
                $authorName = htmlspecialchars($row['author_name']);
                $genreName = htmlspecialchars($row['genre_name']);
                $publisherName = htmlspecialchars($row['publisher_name']);
                $copyright = htmlspecialchars($row['copyright']);
                $isAvailable = htmlspecialchars($row['is_available']);
                $titleAbbrev = substr($title, 0, 1);
                echo "<div onclick=\"
                            showDetails('$title', 
                            '$bookId', 
                            '$authorName', 
                            '$genreName', 
                            '$publisherName', 
                            '$copyright', 
                            '$titleAbbrev',
                            '$isAvailable')\"
                           class=\"result__detail\">
                           <div class=\"grid result__grid gap--md\">
                                <div class=\"book__profile flex justify--center align--center\">$titleAbbrev</div>
                                <div class=\"justify--between align--center book__result\">
                                    <div class=\"book__details\">
                                        <span class=\"book__title\">$title</span>
                                        <br />
                                        <span class=\"book__detail\">$authorName</span>
                                        <br />
                                        <span class=\"book__detail book__genre\">$genreName</span>
                                        <br />
                                        <span class=\"book__detail\">Book ID: <span class=\"result__id\">$bookId</span></span>
                                    </div>
                                     <button class=\"btn btn--accent book__view\">View</button>
                                </div>
                           </div>
                    </div>";
            }
        } else {
            echo "<p>No matches found.</p>";
        }
        ?>
    </div>
    <div class="flex justify--between align--center block__optimizer container results__optimizer">
        <div>
            <p class="page__info">
                Showing <?php echo $offset + 1 ?> - <?php echo min($offset + $resultsPerPage, $totalRows) ?> of <?php echo $totalRows ?> results
            </p>
            <form method="get" class="results__page">
                <label for="resultsPerPage" class="result__label">Results per page</label>
                <select name="resultsPerPage" id="resultsPerPage" onchange="this.form.submit()" class="input container__select">
                    <option value="5" <?php echo $resultsPerPage == 5 ? 'selected' : ''; ?>>5</option>
                    <option value="10" <?php echo $resultsPerPage == 10 ? 'selected' : ''; ?>>10</option>
                    <option value="15" <?php echo $resultsPerPage == 15 ? 'selected' : ''; ?>>15</option>
                    <option value="20" <?php echo $resultsPerPage == 20 ? 'selected' : ''; ?>>20</option>
                </select>
                <input type="hidden" name="text" value="<?php echo $searchParam; ?>">
                <input type="hidden" name="filter" value="<?php echo $filterParam; ?>">
                <input type="hidden" name="page-nr" value="1">
            </form>
        </div>
        <div class="pagination">
            <div class="flex justify--center pagination__switch">
                <div class="page__numbers">
                    <?php
                    $windowSize = 2;
                    $startPage = max(1, $currentPage - $windowSize);
                    $endPage = min($pages, $currentPage + $windowSize);
                    if ($currentPage - $windowSize < 1) {
                        $endPage = min($pages, $endPage + (1 - ($currentPage - $windowSize)));
                        $startPage = 1;
                    }
                    if ($currentPage + $windowSize > $pages) {
                        $startPage = max(1, $startPage - (($currentPage + $windowSize) - $pages));
                        $endPage = $pages;
                    }
                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="?page-nr=<?php echo $i ?>&text=<?php echo $searchParam ?>&filter=<?php echo $filterParam ?>&resultsPerPage=<?php echo $resultsPerPage ?>" class="justify--center align--center pagination__number <?php echo $currentPage === $i ? 'active' : 'none' ?>"><?php echo $i ?></a>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="flex justify--center align--center gap--sm pagination__btns">
                <a href="?page-nr=1&text=<?php echo $searchParam ?>&filter=<?php echo $filterParam ?>&resultsPerPage=<?php echo $resultsPerPage ?>" class="link btn pagination__btn"><i class="fa-solid fa-angles-left"></i></a>
                <a href="?page-nr=<?php echo max(1, $currentPage - 1) ?>&text=<?php echo $searchParam ?>&filter=<?php echo $filterParam ?>&resultsPerPage=<?php echo $resultsPerPage ?>" class="link btn pagination__btn"><i class="fa-solid fa-arrow-left"></i></a>
                <div class="btn pagination__none"></div>
                <a href="?page-nr=<?php echo min($pages, $currentPage + 1) ?>&text=<?php echo $searchParam ?>&filter=<?php echo $filterParam ?>&resultsPerPage=<?php echo $resultsPerPage ?>" class="link btn pagination__btn"><i class="fa-solid fa-arrow-right"></i></a>
                <a href="?page-nr=<?php echo $pages ?>&text=<?php echo $searchParam ?>&filter=<?php echo $filterParam ?>&resultsPerPage=<?php echo $resultsPerPage ?>" class="link btn pagination__btn"><i class="fa-solid fa-angles-right"></i></a>
            </div>
        </div>
    </div>
    <footer>
        <div class="block footer__container">
            <div class="grid grid--1x2 grid--center gap--lg align--center container--lg">
                <div class="flex align--center footer__links">

                    <i class="fa-solid fa-user-large footer__user"></i>
                    <ul>
                        <li class="footer__list"><a href="index.php" class="link footer__link">Home</a></li>
                        <li class="footer__list"><a href="history.php" class="link footer__link">History</a></li>
                        <li class="footer__list"><a href="search.php" class="link footer__link">Search</a></li>
                        <li class="footer__list"><a href="reserveBooks.php" class="link footer__link">Reserve</a></li>
                    </ul>
                </div>
                <div>
                    <div class="flex gap--lg align--center footer__logo">
                        <img src="assets/kalbooks-logo.png" class="img footer__img" alt="">
                        <span class="footer__heading">K.A.L. Books</span>
                    </div>
                    <p class="footer__copyright">Copyright &copy; 2024. Powerede by the 12 - Integrity PWU ICT.</p>
                </div>
            </div>
        </div>
    </footer>
    <div class="flex--column justify--between page__tab">
        <i class="fa-solid fa-xmark icon icon--close page__close" onclick="closeDetails()"></i>
        <div class="page__details"></div>
    </div>
</body>

</html>