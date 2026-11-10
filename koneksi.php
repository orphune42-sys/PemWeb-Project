<?php

$host = 'localhost';
$port = '3306';
$db = 'findyourpath';
$user = 'rindra';
$password = '345678';
$dbCharset = 'utf8mb4';


$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$dbCharset";

$options = [
    PDO::ATTR_ERRMODE
    => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    error_log($e->getMessage());
    
    http_response_code(500);
    $pesan = 'Koneksi basis data gagal.';

    if (stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['success' => false, 'message' => $pesan]);
    } else {
        echo $pesan;
    }
    exit;
}

unset($password, $dsn, $options);
