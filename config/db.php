<?php
session_start();
$host = 'localhost';
$db   = 'laundrygo';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(Exception $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>