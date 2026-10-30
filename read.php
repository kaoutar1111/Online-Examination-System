<?php
// listQuestions.php
header("Content-Type: application/json");

// Connect to the database
$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");

// Fetch all questions ordered by creation date (newest first)
$res = $conn->query("SELECT * FROM questions ORDER BY created_at DESC");

$questions = [];

while ($q = $res->fetch_assoc()) {
  $q['alternatives'] = json_decode($q['alternatives'], true); // Decode JSON options
  $questions[] = $q;
}

echo json_encode($questions);
?>