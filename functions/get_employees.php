<?php
header('Content-Type: application/json');

// Подключение к базе данных
$conn = new mysqli('localhost', 'root', '', 'supsurf'); // Пожалуйста, измените параметры подключения на свои

// Проверка соединения
if ($conn->connect_error) {
    die(json_encode(["error" => "Ошибка подключения: " . $conn->connect_error]));
}

// Получение данных сотрудников
$sql = "SELECT id, name, position, hire_date FROM employees";
$result = $conn->query($sql);

$employees = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Закрытие соединения
$conn->close();

// Возвращаем данные в формате JSON
echo json_encode($employees);
?>