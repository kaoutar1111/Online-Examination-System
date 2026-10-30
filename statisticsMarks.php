<?php
header("Content-Type: application/json");
$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
    echo json_encode(["error" => "Failed to connect to Database"]);
    exit;
}

$sql = "
  SELECT e.title, ROUND(AVG(r.mark), 2) AS media
  FROM results r
  JOIN exams e ON e.test_id = r.test_id
  GROUP BY r.test_id
";

$res = $conn->query($sql);
if (!$res) {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
    $conn->close();
    exit;
}

$data = [];

while ($row = $res->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>