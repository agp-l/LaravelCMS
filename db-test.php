<?php

$host = '127.0.0.1';
$db   = 'dobrodruzicz5';
$user = 'dobrodruzi.cz';
$pass = '2Ie5KVZ7';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;port=3306;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    echo "<p style='color:green;'>✅ Připojení k databázi bylo úspěšné!</p>";
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Chyba připojení: " . $e->getMessage() . "</p>";
}
