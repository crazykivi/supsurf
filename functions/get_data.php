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
    echo "Database connection error: " . $e->getMessage();
    exit();
}

$date = $_GET['date'] ?? null;

if ($date) {
    //$sql = "SELECT * FROM bookings WHERE DATE(date) = :date and status!='Reserved' ORDER BY date;";
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
WHERE 
    DATE(b.date) = :date AND b.status != 'Reserved'
ORDER BY 
    b.date ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':date', $date);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo json_encode([]);
}
