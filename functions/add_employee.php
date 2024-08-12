<?php
header('Content-Type: application/json');

// Декодирование данных из POST-запроса
$data = json_decode(file_get_contents('php://input'), true);

// Проверка метода запроса
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из запроса
    $name = isset($data['name']) ? $data['name'] : '';
    $position = isset($data['position']) ? $data['position'] : '';
    $hireDate = date('Y-m-d'); // Устанавливаем текущую дату

    // Подключение к базе данных
    $conn = new mysqli('localhost', 'root', '', 'supsurf');

    // Проверка соединения
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Ошибка подключения: " . $conn->connect_error]);
        exit();
    }

    // Подготовка и выполнение запроса на добавление сотрудника
    $stmt = $conn->prepare("INSERT INTO employees (name, position, hire_date) VALUES (?, ?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Ошибка подготовки запроса: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("sss", $name, $position, $hireDate);

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
