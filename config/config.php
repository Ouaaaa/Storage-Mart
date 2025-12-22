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

$host = $_ENV['MYSQLHOST'] ?? getenv('MYSQLHOST');
$port = $_ENV['MYSQLPORT'] ?? getenv('MYSQLPORT') ?? 3306;
$db   = $_ENV['MYSQLDATABASE'] ?? getenv('MYSQLDATABASE');
$user = $_ENV['MYSQLUSER'] ?? getenv('MYSQLUSER');
$pass = $_ENV['MYSQLPASSWORD'] ?? getenv('MYSQLPASSWORD');

if (!$host || !$db || !$user) {
    die('Database environment variables not set');
}

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

return $conn;
