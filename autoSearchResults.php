<?php
include 'database.php';

$search = $_POST['text'] ?? ($_GET['text'] ?? '');
$filter = $_POST['filter'] ?? ($_GET['filter'] ?? 'title');
$pattern = '.*' . mysqli_real_escape_string($conn, $search) . '.*';

$joinSQL = "SELECT b.title, 
                   b.book_id,
                   b.copyright, 
                   g.name AS genre_name, 
                   a.name AS author_name, 
                   p.name AS publisher_name
            FROM books b
            JOIN genre g ON b.genre_id = g.genre_id
            JOIN book_authors ba ON ba.book_id = b.book_id
            JOIN authors a ON ba.author_id = a.author_id
            JOIN book_publishers bp ON bp.book_id = b.book_id
            JOIN publishers p ON bp.publisher_id = p.publisher_id";

switch ($filter) {
    case 'book_id':
        $sql = "$joinSQL
                WHERE b.book_id REGEXP '$pattern' LIMIT 5";
        break;
    case 'author':
        $sql = "$joinSQL
                WHERE a.name REGEXP '$pattern' LIMIT 5";
        break;
    case 'genre':
        $sql = "$joinSQL
                WHERE g.name REGEXP '$pattern' LIMIT 5";
        break;
    case 'publisher':
        $sql = "$joinSQL
                WHERE p.name REGEXP '$pattern' LIMIT 5";
        break;
    default:
        $sql = "$joinSQL 
                WHERE title REGEXP '$pattern' LIMIT 5";
        break;
}

$result = mysqli_query($conn, $sql);
$rows = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    if ($rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $title = htmlspecialchars($row['title']);
            $bookId = htmlspecialchars($row['book_id']);
            $authorName = htmlspecialchars($row['author_name']);
            $genreName = htmlspecialchars($row['genre_name']);
            $publisherName = htmlspecialchars($row['publisher_name']);
            $copyright = htmlspecialchars($row['copyright']);

            echo "<a href='searchResults.php?book_id=$bookId&text=$search&filter=$filter' class=\"autosearch__link\"><div class=\"autosearch__result\">$title</div></a>";
        }
    } else {
        echo "<p class=\"autosearch--error\">No matches found.</p>";
    }

    ?>
</body>

</html>