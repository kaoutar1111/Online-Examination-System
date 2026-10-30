<?php
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
  echo json_encode(["error" => "Error while trying to connect"]);
  exit;
}

$stmt = $conn->prepare("INSERT INTO manual_grading (student_id, question_id, response, mark) VALUES (?, ?, '', ?)");
$stmt->bind_param("iid", $data['student_id'], $data['question_id'], $data['mark']);
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Failed to insert: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>