<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['newPassword'])) {
    echo json_encode(["error" => "incomplete data"]);
    exit;
}

$email = $data['email'];
$newPassword = password_hash($data['newPassword'], PASSWORD_DEFAULT);

$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
    echo json_encode(["error" => "Error while trying to connect"]);
    exit;
}

$sql = "UPDATE user SET password = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $newPassword, $email);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Error updating password"]);
}

$stmt->close();
$conn->close();
?>
