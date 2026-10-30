<?php
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
  echo json_encode(["success" => false, "error" => "Failed to connect"]);
  exit;
}

$student_id = 1;
$reason = $data['reason'] ?? 'unknown';
$attempts = $data['attempts'] ?? 1;

$stmt = $conn->prepare("INSERT INTO attempts_cheating (student_id, reason, attempts) VALUES (?, ?, ?)");
$stmt->bind_param("isi", $student_id, $reason, $attempts);
$stmt->execute();

echo json_encode(["success" => true]);
$stmt->close();
$conn->close();
?>