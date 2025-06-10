<?php

$host = '34.205.74.0';
$db   = 'SeatlookFor';
$user = 'root';
$pass = 'password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "¡Conexión exitosa!\n";
    echo "Versión del servidor: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
} catch (\PDOException $e) {
    echo "Error de conexión: " . $e->getMessage() . "\n";
} 