<?php
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

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Error deleting user."]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "ID not provided."]);
}

$conn->close();
?>
