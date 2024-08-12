<?php
$host = 'localhost';
$dbname = 'supsurf';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");
} catch (PDOException $e) {
    echo json_encode(['message' => "Database connection error: " . $e->getMessage()]);
    exit();
}

//$sql = "SELECT * FROM bookings";
$sql = "SELECT 
    b.id, 
    b.date, 
    b.endTime, 
    b.status, 
    t.service
FROM 
    supsurf.bookings AS b
LEFT JOIN 
    supsurf.types AS t ON b.idTypes = t.idTypes
ORDER BY 
    b.date ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($bookings);
