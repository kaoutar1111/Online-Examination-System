<?php
header("Content-Type: application/json");

// Get JSON input data
$data = json_decode(file_get_contents("php://input"), true);

// Check if required fields are present
if (!isset($data['email']) || !isset($data['name'])) {
    echo json_encode(["valid" => false, "error" => "Missing data"]);
    exit;
}

$email = trim($data['email']);
$name = trim(strtolower($data['name']));

// Connect to the database
$conn = new mysqli("localhost", "root", "ABC123!", "onlinesystem");
if ($conn->connect_error) {
    echo json_encode(["valid" => false, "error" => "Database connection failed"]);
    exit;
}

// Prepare and execute the query
$sql = "SELECT id FROM user WHERE email = ? AND LOWER(name) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $name);
$stmt->execute();
$result = $stmt->get_result();

// Check if a matching record was found
if ($result->fetch_assoc()) {
    echo json_encode(["valid" => true]);
} else {
    echo json_encode(["valid" => false]);
}

$stmt->close();
$conn->close();
?>