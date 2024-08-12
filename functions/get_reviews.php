<?php
$host = 'localhost';
$dbname = 'supsurf';
$username = 'root';
$password = '';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'");

    $stmt = $pdo->prepare("
        SELECT r.id, r.rating, r.review, r.created_at, t.service, i.name as instructor_name
        FROM reviews r
        JOIN types t ON r.idTypes = t.idTypes
        JOIN instructors i ON r.instructorid = i.id
        WHERE r.approval = 'Одобрено'
        ORDER BY r.created_at DESC
        LIMIT 3
    ");
    $stmt->execute();

    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($reviews);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
