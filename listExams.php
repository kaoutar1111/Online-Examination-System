<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
  echo json_encode(["error" => "Error connecting: " . $conn->connect_error]);
  exit;
}

// Verify if table exist
$tableExist = $conn->query("SHOW TABLES LIKE 'exams'");
if ($tableExist->num_rows == 0) {
  echo json_encode(["error" => "Table 'exams' not found in the database."]);
  exit;
}

$sql = "SELECT * FROM exams 
        WHERE published = 1 
        AND NOW() BETWEEN start_date AND end_date 
        ORDER BY start_date ASC";

$res = $conn->query($sql);
$exams = [];

if ($res && $res->num_rows > 0) {
  while ($row = $res->fetch_assoc()) {
    $exams[] = $row;
  }
  echo json_encode($exams);
} else {
  echo json_encode([]);
}
?>
