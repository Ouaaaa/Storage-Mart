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
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
    ]);
} catch (PDOException $e) {
    error_log('StorageMart DB connection failed: ' . $e->getMessage());
    http_response_code(503);
    die("Service temporarily unavailable. Please try again later.");
}