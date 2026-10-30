<?php
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$password = "ABC123!";
$system = "onlinesystem";

$conn = new mysqli($host, $user, $password, $system);

if ($conn->connect_error) {
    echo json_encode(["error" => "Failed to connect: " . $conn->connect_error]);
    exit;
}

$sql = "SELECT id, name, email, role, creation_date FROM user ORDER BY id ASC";
$result = $conn->query($sql);

$user = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $user[] = $row;
    }
}

echo json_encode($user);

$conn->close();
?>
