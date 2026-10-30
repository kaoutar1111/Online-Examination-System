<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");

$sql = "
SELECT r.id, r.student_id, r.question_id, r.given_answer, q.testpaper 
FROM answers r
JOIN questions q ON q.id = r.question_id
LEFT JOIN manual_correction c ON c.question_id = r.question_id AND c.student_id = r.student_id
WHERE q.type IN ('short_answer', 'fill_in') AND c.id IS NULL
";

$res = $conn->query($sql);
$datas = [];

while ($row = $res->fetch_assoc()) {
  $datas[] = $row;
}

echo json_encode($datas);
?>