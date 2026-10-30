<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$res = $conn->query("SELECT question_ids FROM test WHERE id = $id");
if ($row = $res->fetch_assoc()) {
  $ids = json_decode($row['question_ids']);
  $placeholders = implode(',', array_fill(0, count($ids), '?'));
  $stmt = $conn->prepare("SELECT * FROM questions WHERE id IN ($placeholders)");
  $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
  $stmt->execute();
  $result = $stmt->get_result();
  $questions = [];
  while ($q = $result->fetch_assoc()) {
    $questions[] = $q;
  }
  echo json_encode(["success" => true, "questions" => $questions]);
} else {
  echo json_encode(["success" => false, "error" => "test not found"]);
}
$conn->close();
?>