<?php
// listQuestions.php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
$res = $conn->query("SELECT * FROM quesions ORDER BY created_in DESC");
$questions = [];
while ($q = $res->fetch_assoc()) {
  $q['alternatives'] = json_decode($q['alternatives'], true);
  $questions[] = $q;
}
echo json_encode($questions);

?>