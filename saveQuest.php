<?php
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
    echo json_encode(["error" => "Failed to connect"]);
    exit;
}

$type = $data['type'];
$testpaper = $data['testpaper'];
$alternatives = isset($data['alternatives']) ? json_encode($data['alternatives']) : null;
$response = $data['response'];

$stmt = $conn->prepare("INSERT INTO questions (type, testpaper, alternatives, correct_answer) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $type, $testpaper, $alternatives, $response);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Error saving"]);
}

$stmt->close();
$conn->close();
?>
