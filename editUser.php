<?php
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$password = "ABC123!";
$system = "onlinesystem";

$conn = new mysqli($host, $user, $password, $system);

if ($conn->connect_error) {
    echo json_encode(["error" => "Failed to connect"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'], $data['name'], $data['email'], $data['role'])) {
    $id = intval($data['id']);
    $name = $data['name'];
    $email = $data['email'];
    $role = $data['role'];

    $sql = "UPDATE user SET name = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $email, $role, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Error updating."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Incomplete Data."]);
}

$conn->close();
?>
