<?php

header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
  echo json_encode([]);
  exit;
}
$res = $conn->query("SELECT id, created_in FROM tests ORDER BY created_in DESC");
$tests = [];
while ($p = $res->fetch_assoc()) {
  $tests[] = $p;
}
echo json_encode($tests);

?>