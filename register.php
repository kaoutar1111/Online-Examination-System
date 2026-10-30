<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$password = "ABC123!";
$system = "onlinesystem";

$conn = new mysqli($host, $user, $password, $system);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Failed to connect to database: " . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['name'], $data['email'], $data['password'], $data['role'])) {
    $name = $data['name'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $role = $data['role'];

    $sql = "INSERT INTO user (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(["success" => false, "error" => "Prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Execution failed: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Incomplete data."]);
}

$conn->close();
