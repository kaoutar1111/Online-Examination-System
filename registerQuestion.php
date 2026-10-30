<?php
header("Content-Type: application/json");

// Database connection
$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Read and decode input
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['testpaper'], $data['type'], $data['response'])) {
    $testpaper = $data['testpaper'];
    $type = $data['type'];
    $response = $data['response'];
    $level = $data['level'] ?? 'medio';
    $category = $data['category'] ?? '';
    $alternatives = isset($data['alternatives']) ? json_encode($data['alternatives']) : null;

    // Prepare and execute insert
    $stmt = $conn->prepare("INSERT INTO questions (testpaper, type, alternatives, response_correta, nivel, categoria)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $testpaper, $type, $alternatives, $response, $level, $category);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Insert error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Missing data. Required: testpaper, type, response"]);
}

$conn->close();
?>
