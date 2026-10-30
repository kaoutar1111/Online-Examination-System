<?php
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
  echo json_encode(["error" => "Connection failed"]);
  exit;
}

$stmt = $conn->prepare("INSERT INTO exams (title, test_id, start_date, end_date, published) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sissi", $data['title'], $data['test_id'], $data['start_date'], $data['end_date'], $data['published']);

if ($stmt->execute()) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["error" => "Failed to save exam"]);
}

$stmt->close();
$conn->close();
?>