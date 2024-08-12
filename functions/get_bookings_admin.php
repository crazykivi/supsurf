<?php
$host = 'localhost';
$dbname = 'supsurf';
$username = 'root';
$password = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES 'utf8'");

$date = $_GET['date'];

$query = $pdo->prepare("SELECT id, date, status FROM bookings WHERE DATE(date) = DATE(:date)");
$query->execute(['date' => $date]);
$bookings = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($bookings);
