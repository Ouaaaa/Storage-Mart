<?php

define('BASE_URL', '');

$db_host = getenv('MYSQLHOST') ?: '127.0.0.1';
$db_port = getenv('MYSQLPORT') ?: 3306;
$db_name = getenv('MYSQLDATABASE') ?: 'storagemart';
$db_user = getenv('MYSQLUSER') ?: 'root';
$db_pass = getenv('MYSQLPASSWORD') ?: '';

$dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Database connection failed");
}

