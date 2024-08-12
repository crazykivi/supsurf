<?php
header('Content-Type: application/json');

// Декодирование данных из POST-запроса
$data = json_decode(file_get_contents('php://input'), true);

// Проверка метода запроса
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из запроса
    $username = isset($data['username']) ? $data['username'] : '';
    $password = isset($data['password']) ? $data['password'] : '';
    $createdAt = date('Y-m-d H:i:s'); // Устанавливаем текущую дату и время

    // Проверка на наличие обязательных данных
    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(["success" => false, "error" => "Имя пользователя и пароль обязательны"]);
        exit();
    }

    // Подключение к базе данных
    $conn = new mysqli('localhost', 'root', '', 'supsurf');

    // Проверка соединения
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Ошибка подключения: " . $conn->connect_error]);
        exit();
    }

    // Подготовка и выполнение запроса на добавление учетной записи
    $stmt = $conn->prepare("INSERT INTO users (username, password, created_at) VALUES (?, ?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Ошибка подготовки запроса: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("sss", $username, $password, $createdAt);

    // Выполнение запроса и проверка результата
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Ошибка выполнения запроса: " . $stmt->error]);
    }

    // Закрытие соединения
    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Некорректный метод запроса"]);
}
