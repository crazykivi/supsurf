<?php
$host = 'localhost';
$dbname = 'supsurf';
$username = 'root';
$password = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("SET NAMES 'utf8'");

$data = json_decode(file_get_contents("php://input"), true);
$bookingId = $data['bookingId'];
$newStatus = $data['status'];

$query = $pdo->prepare("UPDATE bookings SET status = :status WHERE id = :id");
$query->execute(['status' => $newStatus, 'id' => $bookingId]);

echo json_encode(['message' => "Статус успешно изменен на $newStatus"]);
