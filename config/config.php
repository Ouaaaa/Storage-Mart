<?php

define('BASE_URL', '');
echo '<pre>';
var_dump([
    'MYSQLHOST' => getenv('MYSQLHOST'),
    'MYSQLPORT' => getenv('MYSQLPORT'),
    'MYSQLDATABASE' => getenv('MYSQLDATABASE'),
    'MYSQLUSER' => getenv('MYSQLUSER'),
    'MYSQLPASSWORD' => getenv('MYSQLPASSWORD') ? 'SET' : 'NOT SET'
]);
exit;

$db_host = getenv('MYSQLHOST') ?: '127.0.0.1';
$db_port = getenv('MYSQLPORT') ?: 3306;
$db_name = getenv('MYSQLDATABASE') ?: 'storagemart';
$db_user = getenv('MYSQLUSER') ?: 'root';
$db_pass = getenv('MYSQLPASSWORD') ?: '';

try {
    $pdo = new PDO(
        "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed');
}

