<?php

define('BASE_URL', '');

// Environment-aware DB config
if (getenv('MYSQLHOST')) {
    // Railway
    $db_host = getenv('MYSQLHOST');
    $db_port = getenv('MYSQLPORT') ?: 3306;
    $db_name = getenv('MYSQLDATABASE');
    $db_user = getenv('MYSQLUSER');
    $db_pass = getenv('MYSQLPASSWORD');
} else {
    // Local XAMPP
    $db_host = '127.0.0.1';
    $db_port = 3306;
    $db_name = 'storagemart';
    $db_user = 'root';
    $db_pass = '';
}

try {
    $pdo = new PDO(
        "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed');
}
