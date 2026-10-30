<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
  echo json_encode(["error" => "Connection Error"]);
  exit;
}

$student_id = 1;
$test_id = $_GET['test_id'] ?? 0;

$total_questions = 0;
$total_right = 0;

$sql = "SELECT r.question_id, r.given_answer, q.correct_answer, q.type 
        FROM answers r
        JOIN questions q ON q.id = r.question_id
        WHERE r.student_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
  $total_questions++;
  $student_answer = trim(strtolower($row['given_answer']));
  $correct_answer = trim(strtolower($row['correct_answer']));
  $type = $row['type'];

  if ($type === 'multiple') {
    $student_answer = implode(',', array_unique(explode(',', $student_answer)));
    $correct_answer = implode(',', array_unique(explode(',', $correct_answer)));
  }

  if ($student_answer === $correct_answer) {
    $total_right++;
  }
}

// Calcular nota
$mark = ($total_questions > 0) ? round(($total_right / $total_questions) * 10, 2) : 0;

// Salvar resultado
$stmt = $conn->prepare("INSERT INTO results (student_id, test_id, mark) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $student_id, $test_id, $mark);
$stmt->execute();

echo json_encode(["success" => true, "mark" => $mark]);
?>