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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $notes = $_POST["notes"];
    $bookingId = $_POST["bookingId"]; 

    $query = "INSERT INTO registration (booking_id, name, phone, notes, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $status = "Новая";
    $stmt->execute([$bookingId, $name, $phone, $notes, $status]);

    if ($stmt) {
        $response = array("success" => true, "message" => "Заявка успешно оформлена.");
        echo json_encode($response);
    } else {
        $response = array("success" => false, "message" => "Произошла ошибка при оформлении заявки.");
        echo json_encode($response);
    }
}

