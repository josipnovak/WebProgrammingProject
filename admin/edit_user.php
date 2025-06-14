<?php
session_start();
include "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = intval($data['user_id'] ?? 0);
$username = $data['username'] ?? '';
$role = $data['role'] ?? '';

$stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
$stmt->bind_param("ssi", $username, $role, $user_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}

?>