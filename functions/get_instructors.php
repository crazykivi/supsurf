<?php
header('Content-Type: application/json');

// Подключение к базе данных
$conn = new mysqli('localhost', 'root', '', 'supsurf');

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение данных инструкторов
$sql = "SELECT id, name FROM instructors";
$result = $conn->query($sql);

$instructors = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $instructors[] = $row;
    }
}

// Закрытие соединения
$conn->close();

// Возвращаем данные в формате JSON
echo json_encode($instructors);
?>
