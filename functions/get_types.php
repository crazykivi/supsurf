<?php
header('Content-Type: application/json');

// Подключение к базе данных
$conn = new mysqli('localhost', 'root', '', 'supsurf');

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение данных типов плавания
$sql = "SELECT idTypes, service FROM types";
$result = $conn->query($sql);

$types = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $types[] = $row;
    }
}

// Закрытие соединения
$conn->close();

// Возвращаем данные в формате JSON
echo json_encode($types);
