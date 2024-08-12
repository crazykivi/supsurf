<?php
header('Content-Type: application/json');

// Подключение к базе данных
$conn = new mysqli('localhost', 'root', '', 'supsurf');

// Проверка соединения
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Ошибка подключения: " . $conn->connect_error]);
    exit();
}

// Запрос на получение учетных записей
$result = $conn->query("SELECT id, username, created_at FROM users");

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode($users);

// Закрытие соединения
$conn->close();
?>
