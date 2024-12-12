<?php

session_start();
include 'database.php';

$userId = $_POST['userId'];
$password = $_POST['password'];

if (empty($userId)) {
    header('Location: login.php?error=Please enter user id');
    exit();
}
if (empty($password)) {
    header('Location: login.php?error=Please enter password');
    exit();
}

$sql = "SELECT *
        FROM users
        WHERE user_id='$userId' AND password = '$password'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    if ($row['user_id'] === $userId && $row['password'] === $password) {
        $firstName = $row['first_name'];
        $lastName = $row['last_name'];
        $fullName = $firstName . " " . $lastName;

        $_SESSION['full_name'] = $fullName;
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $lastName;
        $_SESSION['password'] = $row['password'];
        $_SESSION['user_type'] = $row['user_type_id'];
        header('Location: index.php?success=Logged In Successfully');
        exit();
    }
} else {
    header("Location: login.php?error=Incorrect user id or password.");
    exit();
}
