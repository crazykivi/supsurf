<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $promotionId = $data['promotionId'];
    $employeeId = $data['employeeId'];

    // Подключение к базе данных
    $conn = new mysqli('localhost', 'root', '', 'supsurf');

    // Проверка соединения
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Ошибка подключения: " . $conn->connect_error]);
        exit();
    }

    // Начало транзакции
    $conn->begin_transaction();

    try {
        // Удаление связи с сотрудником
        $stmt = $conn->prepare("DELETE FROM employees_responsible_for_promotions WHERE promotion_id = ? AND employee_id = ?");
        $stmt->bind_param("ii", $promotionId, $employeeId);

        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }

        // Удаление акции
        $stmt = $conn->prepare("DELETE FROM promotions WHERE id = ?");
        $stmt->bind_param("i", $promotionId);

        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }

        // Фиксация транзакции
        $conn->commit();

        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        // Откат транзакции в случае ошибки
        $conn->rollback();
        http_response_code(500);
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }

    // Закрытие соединения
    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Некорректный метод запроса"]);
}
?>
