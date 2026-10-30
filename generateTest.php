<?php
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);
$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");

if ($data['mode'] === 'manual') {
  $ids = json_encode($data['ids']);
} else {
  $type = $data['type'];
  $qtd = intval($data['quantity']);
  $sql = "SELECT id FROM questions WHERE type = ? ORDER BY RAND() LIMIT ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("si", $type, $qtd);
  $stmt->execute();
  $res = $stmt->get_result();
  $ids = [];
  while ($row = $res->fetch_assoc()) {
    $ids[] = $row['id'];
  }
  $ids = json_encode($ids);
}

$conn->query("CREATE TABLE IF NOT EXISTS tests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  questions_ids TEXT,
  created_in TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$stmt = $conn->prepare("INSERT INTO tests (questions_ids) VALUES (?)");
$stmt->bind_param("s", $ids);

if ($stmt->execute()) echo json_encode(["success" => true]);
else echo json_encode(["error" => "Fail"]);

?>