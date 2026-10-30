<?php
session_start();
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$password = "ABC123!";
$system = "onlinesystem";

$conn = new mysqli($host, $user, $password, $system);
if ($conn->connect_error) {
    echo json_encode(["error" => "Failed to connect"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["error" => "email or password not saved."]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

$sql = "SELECT * FROM user WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "password not correct."]);
    }
} else {
    echo json_encode(["error" => "User not found."]);
}

$stmt->close();
$conn->close();
?>
