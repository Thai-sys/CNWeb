<?php
// config/config.php

$host = 'localhost';
$db   = 'coffee-time'; // Tên cơ sở dữ liệu
$user = 'root';        // Tên đăng nhập
$pass = '';            // Mật khẩu
$charset = 'utf8mb4';

// Thiết lập DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
