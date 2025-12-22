<?php

define('BASE_URL', '');


$host = getenv('MYSQLHOST');
$port = getenv('MYSQLPORT');
$db   = getenv('MYSQL_DATABASE'); // IMPORTANT
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed");
}

$link = mysqli_connect($host, $user, $pass, $db);

if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}
