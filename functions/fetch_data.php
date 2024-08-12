<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supsurf";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT r.id, r.name, r.status, b.date, r.phone, r.notes, r.created_at, b.status AS booking_status 
        FROM supsurf.registration r
        JOIN supsurf.bookings b ON r.booking_id = b.id
        WHERE b.date >= CURDATE()";
$result = $conn->query($sql);

$output = '';
$output = '<tr>
<th>Имя</th>
<th>Статус</th>
<th>Дата</th>
</tr>';
while ($row = $result->fetch_assoc()) {
    $output .= "<tr onclick='showModal(" . json_encode($row) . ")'>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['status']) . "</td>
                    <td>" . htmlspecialchars($row['date']) . "</td>
                </tr>";
}
echo $output;
$conn->close();
