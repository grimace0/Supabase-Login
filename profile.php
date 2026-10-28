<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$errors = [];
$success = "";

$username = $_SESSION['username'];


$stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE username = ?');
mysqli_stmt_bind_param($stmt, 's', $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $address = trim($_POST['address']); 
    $phonenum = trim($_POST['phonenum']);
    $gender = trim($_POST['gender']);

    if (empty($address)) {
        $errors[] = "Address is required";
    }

    if (empty($phonenum)) {
        $errors[] = "Phone number is required";
    }

    if (!ctype_digit($phonenum)) {
        $errors[] = "Phone number must contain only digits";
    }

    if (strlen($phonenum) != 11) {
        $errors[] = "Phone number must be exactly 11 digits";
    }

    if (empty($errors)) {
       
        $update_stmt = mysqli_prepare($conn, 'UPDATE users SET address = ?, phone_number = ?, gender = ? WHERE username = ?');
        mysqli_stmt_bind_param($update_stmt, 'ssss', $address, $phonenum, $gender, $username);
        mysqli_stmt_execute($update_stmt);

        $success = "Profile updated successfully";
        
        $user['address'] = $address;
        $user['phone_number'] = $phonenum;
        $user['gender'] = $gender;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>

<body>
    <h3>Edit Profile</h3>
    
    <?php
    if ($success) {
        echo "<p style='color: green;'>" . $success . "</p>";
    }

    foreach ($errors as $error) {
        echo "<p style='color: red;'>" . $error . "</p>";
    }
    ?>

    <form method="POST">
        <label for="address">Address:</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
        
        <br><br>
        
        <label for="phonenum">Phone Number:</label>
        <input type="text" name="phonenum" inputmode="numeric" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>">
        
        <br><br>
        
        <label for="gender">Gender:</label>
        <select name="gender">
            <option value="male" <?php echo (isset($user['gender']) && $user['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo (isset($user['gender']) && $user['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
        </select>
        
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id'] ?? ''); ?>">
        <br><br>
        <button type="submit">Update</button>
    </form>
    
    <br><br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>

</html>