<?php
// Разрешение кросс-доменных запросов (если требуется)
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $instructor = $data['instructor'];
    $type = $data['type'];
    $rating = $data['rating'];
    $review = $data['review'];
    $ip_address = $_SERVER['REMOTE_ADDR']; // Получение IP-адреса пользователя

    // Подключение к базе данных
    $conn = new mysqli('localhost', 'root', '', 'supsurf');

    // Проверка соединения
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["error" => "Ошибка подключения: " . $conn->connect_error]);
        exit();
    }

    // Вставка данных в таблицу reviews
    $stmt = $conn->prepare("INSERT INTO reviews (idTypes, instructorid, rating, review, created_at, ip_address) VALUES (?, ?, ?, ?, NOW(), ?)");

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Ошибка подготовки запроса: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("iiiss", $type, $instructor, $rating, $review, $ip_address);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Отзыв успешно добавлен!"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Ошибка выполнения запроса: " . $stmt->error]);
    }

    // Закрытие соединения
    $stmt->close();
    $conn->close();
}
