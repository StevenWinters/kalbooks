<?php
session_start();
include 'navbar.php';

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    $books = isset($_SESSION['books']) ? $_SESSION['books'] : [];
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kalbooks | Reserve</title>
        <link rel="stylesheet" href="index.css">
        <script src="index.js" defer></script>
    </head>

    <body>
        <section class="block container--lg">
            <?php if (isset($_GET['error'])) { ?>
                <div class="flex justify--center align--center error">
                    <p><i class="fa-solid fa-triangle-exclamation icon--warning"></i><?php echo $_GET['error'] ?></p>
                </div>
            <?php } ?>
            <header class="table__header">
                <h1>Reserve</h1>
                <form action="reserveFunction.php" method="post" class="flex justify--between align--center table__field">
                    <label for="book__id"><i class="fa-solid fa-magnifying-glass table__icon"></i></label>
                    <input type="text" name="bookId" id="book__id" placeholder="Enter Book ID" class="input table__input" value="<?php echo isset($_GET['book-id']) ? $_GET['book-id'] : '' ?>">
                    <button type=" submit" class="btn btn--primary reserve__btn">Add Book</button>
                </form>
            </header>
            <section class="grid grid--1x2 gap--sm notes">
                <div class="note note--primary">
                    <div class="flex align--center gap--sm note__container">
                        <span class="note__icon">
                            <i class="fa-solid fa-lightbulb"></i>
                        </span>
                        <span class="note__heading">Note</span>
                    </div>
                    <p>Students can only reserve/borrow 3 books maximum.</p>
                </div>
                <div class="note note--secondary">
                    <div class="flex align--center gap--sm note__container">
                        <span class="note__icon">
                            <i class="fa-solid fa-lightbulb"></i>
                        </span>
                        <span class="note__heading">Note</span>
                    </div>
                    <p>Teacher/Admin can only reserve/borrow 5 books maximum.</p>
                </div>
            </section>
            <?php
            if ($books) {
            ?>
                <div class="table__container">
                    <table>
                        <thead>
                            <tr>
                                <th class="table__head table__reserve">Reserve Books</th>
                                <th class="table__head" colspan="5"></th>
                                <th class="table__head table__total"><?php echo count($books) ?> <?php echo count($books) === 1 ? 'book' : 'books' ?> total</th>
                            </tr>
                            <tr>
                                <th>Book ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                                <th>Copyright</th>
                                <th>Publisher</th>
                                <th>Delete Book</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book) { ?>
                                <tr>
                                    <td><?php echo $book['book_id']; ?></td>
                                    <td><?php echo $book['title']; ?></td>
                                    <td><?php echo $book['author_name']; ?></td>
                                    <td><?php echo $book['genre_name']; ?></td>
                                    <td><?php echo $book['copyright']; ?></td>
                                    <td><?php echo $book['publisher_name']; ?></td>
                                    <td>
                                        <form action="deleteBooks.php" method="post">
                                            <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                            <button type="submit" class="btn btn--primary">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="grid grid--1x2 gap--sm table__btn">
                    <a href="index.php"><button type="submit" class="btn btn--primary btn--lg">Return to home</button></a>
                    <a href="confirmReserve.php"><button type="submit" class="btn btn--primary btn--lg">Reserve</button></a>
                </div>
            <?php } else { ?>
                <section class="block container table__empty">
                    <p>You have not reserved any books yet.</p>
                </section>
            <?php
            } ?>
        </section>
    </body>

    </html>

<?php
} else {
    header('Location: login.php');
}
?>