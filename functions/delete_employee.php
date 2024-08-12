<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $data['id'];

    // Подключение к базе данных
    $conn = new mysqli('localhost', 'root', '', 'supsurf'); // Пожалуйста, измените параметры подключения на свои

    // Проверка соединения
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Ошибка подключения: " . $conn->connect_error]);
        exit();
    }

    // Удаление сотрудника
    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Ошибка подготовки запроса: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("i", $id);

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
?>
