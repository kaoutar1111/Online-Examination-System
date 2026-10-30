<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
  echo json_encode(["error" => "Failed to connect"]);
  exit;
}

$student_id = 1;

foreach ($_POST as $name => $value) {
  if (preg_match('/q(\\d+)/', $name, $m)) {
    $question_id = $m[1];
    $response = is_array($value) ? implode(',', $value) : $value;

    $stmt = $conn->prepare("INSERT INTO responses (student_id, question_id, given_answer) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $student_id, $question_id, $response);
 if (!$stmt->execute()) {
      echo json_encode(["error" => "Failed to save response for question $question_id"]);
      $stmt->close();
      $conn->close();
      exit;
    }
      $stmt->close();
  }
}

$conn->close();
echo json_encode(["success" => true]);
?>