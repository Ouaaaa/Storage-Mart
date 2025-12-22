<?php

define('BASE_URL', ''); // keep this as you already fixed routing

$db_host = getenv('MYSQLHOST');
$db_port = getenv('MYSQLPORT') ?: 3306;
$db_name = getenv('MYSQLDATABASE');
$db_user = getenv('MYSQLUSER');
$db_pass = getenv('MYSQLPASSWORD');

$dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // TEMP for debugging — remove later
    die("Database connection failed");
}
