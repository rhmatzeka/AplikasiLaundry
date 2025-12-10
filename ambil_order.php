<?php
require 'config/db.php';
if($_SESSION['user']['role'] != 'driver') die("Akses ditolak!");

$id = $_GET['id'];
$pdo->prepare("UPDATE orders SET driver_id = ?, status = 'dijemput' WHERE id = ? AND status = 'menunggu'")
    ->execute([$_SESSION['user']['id'], $id]);

header("Location: dashboard.php");