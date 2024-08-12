<?php
header('Content-Type: application/json');

// Подключение к базе данных
$conn = new mysqli('localhost', 'root', '', 'supsurf');

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получение данных акций
$sql = "
    SELECT 
        p.id,
        p.name AS promotion_name,
        p.start_date,
        p.end_date,
        p.description,
        e.name AS employee_name,
        e.position
    FROM 
        promotions p
    JOIN 
        employees_responsible_for_promotions erp ON p.id = erp.promotion_id
    JOIN 
        employees e ON erp.employee_id = e.id
";
$result = $conn->query($sql);

$promotions = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $promotions[] = $row;
    }
}

// Закрытие соединения
$conn->close();

// Возвращаем данные в формате JSON
echo json_encode($promotions);
?>
