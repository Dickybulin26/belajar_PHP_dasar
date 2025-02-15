<?php

session_start();
require 'functions.php';

//* cek cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['key']) == true) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    //* ambil username berdasarkan id
    $result = mysqli_query($db, "SELECT username FROM user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);

    //* cek cookie dan username
    if ($key === hash('sha256', $row['username'])) {
        $_SESSION['login'] = true;
    }
}

if (isset($_SESSION["login"]) == true) {
    header("Location: index.php");
    exit;
}


if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($db, "SELECT * FROM user WHERE username = '$username'");

    //* cek username
    if (mysqli_num_rows($result) === 1) {

        //* cek password
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {

            //* set session
            $_SESSION["login"] = true;

            //* set remember me (cookie)
            if (isset($_POST['remember'])) {
                setcookie('id', $row['id'], time() + 60);
                setcookie('key', hash('sha256', $row['username']), time() + 60);
            }

            header("Location: index.php");
            exit;
        }
    }

    $error = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <style>
        li {
            list-style: none;
        }
    </style>
</head>

<body>

    <h1>Halaman Login</h1>

    <?php if (isset($error)) : ?>
        <p style="color: red; font-style: italic;">username / password salah</p>
    <?php endif; ?>

    <form action="" method="post">
        <ul>
            <li>
                <label for="username">Username : </label>
                <input type="text" name="username" id="username">
            </li>
            <li>
                <label for="password">Password : </label>
                <input type="password" name="password" id="password">
            </li>
            <li>
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">remember me</label>
            </li>
            <li>
                <button type="submit" name="login">Login</button>
            </li>
            <li>
                belum punya akun? <a href="registrasi.php"><button>Registrasi</button></a>
            </li>
        </ul>
    </form>

</body>

</html>