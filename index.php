
<?php

include('db.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($username)) {
        $errors[] = "Username is required";
    }

    $result = pg_query_params($conn, 'SELECT username FROM public.users WHERE username = $1', [$username]);

    if ($result) {
        if (pg_fetch_assoc($result)) {
            $errors[] = "Username already exist";
        }
    } else {
        $errors[] = "Database query failed";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if ($password != $confirmPassword) {
        $errors[] = "Password did not match";
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    if (empty($errors)) {
        $insert = pg_query_params(
            $conn,
            'INSERT INTO public.users (username, password) VALUES ($1, $2)',
            [$username, $passwordHash]
        );

        if ($insert) {
            echo "Registered Successfully";
        } else {
            $errors[] = "Registration failed";
        }
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
        <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ""; ?>">
        <br><br>

        <label for="password">Password:</label>
        <input type="password" name="password">
        <br><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password">
        <br><br>

        <button type="submit">Register</button>
    </form>

    <br><br>

    <a href="login.php">Login</a>

    <?php

    foreach ($errors as $error) {
        echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "<br>";
    }

    ?>

</body>

</html>
