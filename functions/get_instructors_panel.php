<?php
header('Content-Type: application/json');

// Подключение к базе данных
$conn = new mysqli('localhost', 'root', '', 'supsurf');

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение данных инструкторов и их стилей
$sql = "
    SELECT 
        i.id AS instructor_id,
        i.name AS instructor_name,
        t.idTypes AS type_id,
        t.service AS type_name
    FROM 
        instructors i
    LEFT JOIN 
        instructors_types it ON i.id = it.idInstructor
    LEFT JOIN 
        types t ON it.idTypes = t.idTypes
    ORDER BY 
        i.id, t.idTypes
";
$result = $conn->query($sql);

$instructors = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $instructor_id = $row['instructor_id'];
        if (!isset($instructors[$instructor_id])) {
            $instructors[$instructor_id] = [
                'id' => $instructor_id,
                'name' => $row['instructor_name'],
                'types' => []
            ];
        }
        if ($row['type_id']) {
            $instructors[$instructor_id]['types'][] = [
                'id' => $row['type_id'],
                'name' => $row['type_name']
            ];
        }
    }
}

// Закрытие соединения
$conn->close();

// Возвращаем данные в формате JSON
echo json_encode(array_values($instructors));
?>
