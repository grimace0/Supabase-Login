<?php

$dbHost = 'localhost';
$dbUser = 'root';
$dbPassword = '';
$database = 'login_db';
$dbPort = 3306;


$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $database, $dbPort);

if (!$conn) {
   
    die('Database connection failed: ' . mysqli_connect_error());
}

?>