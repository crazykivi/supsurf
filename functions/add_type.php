<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $instructorId = $data['instructorId'];
    $typeId = $data['typeId'];

    // Подключение к базе данных
    $conn = new mysqli('localhost', 'root', '', 'supsurf');

    // Проверка соединения
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Ошибка подключения: " . $conn->connect_error]);
        exit();
    }

    // Добавление стиля
    $stmt = $conn->prepare("INSERT INTO instructors_types (idInstructor, idTypes) VALUES (?, ?)");
    $stmt->bind_param("ii", $instructorId, $typeId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Ошибка выполнения запроса: " . $stmt->error]);
    }

    // Закрытие соединения
    $stmt->close();
    $conn->close();
}
?>
