<?php
session_start();  

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "supsurf";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["password"])) {
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE password = ?");
    $stmt->bind_param("s", $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["authenticated"] = true;  

        echo "clientele.php";
    } else {
        echo "index";
    }

    $stmt->close();
    $conn->close();
}