<?php
session_start();
include('db.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
        $errors[] = "Username is required";
    }

    $stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE username = ?');
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        }
    }

    if (empty($errors)) {
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo (isset($_POST['username'])) ? $_POST['username'] : ""; ?>">
        <br><br>
        <label for="password">Password:</label>
        <input type="password" name="password">
        <br><br>
        <button type="submit">Login</button>
    </form>
    <br><br>
    <a href="register.php">Register</a>
    <?php

    foreach ($errors as $error) {
        echo $error . "<br>";
    }

    ?>
</body>

</html>