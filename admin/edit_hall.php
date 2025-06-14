<?php
session_start();
include "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$hall_id = intval($data['hall_id'] ?? 0);
$name = $data['name'] ?? '';

$stmt = $conn->prepare("UPDATE hall SET name = ? WHERE id = ?");
$stmt->bind_param("ss", $name, $hall_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}

?>