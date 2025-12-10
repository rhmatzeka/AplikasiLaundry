<?php
require 'config/db.php';
if($_SESSION['user']['role'] != 'driver') die("Akses ditolak!");

$statuses = ['dijemput', 'proses', 'diantar', 'selesai'];
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT status FROM orders WHERE id = ? AND driver_id = ?");
$stmt->execute([$id, $_SESSION['user']['id']]);
$current = $stmt->fetchColumn();

$next = $statuses[array_search($current, $statuses) + 1] ?? 'selesai';
$pdo->prepare("UPDATE orders SET status = ? WHERE id = ?")->execute([$next, $id]);

header("Location: dashboard.php");