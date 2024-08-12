<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supsurf";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && isset($_POST["status"])) {
    $id = $_POST["id"];
    $status = $_POST["status"];

    $stmt = $conn->prepare("UPDATE supsurf.registration SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Success";
    } else {
        echo "Error";
    }
    $stmt->close();
    $conn->close();
}
