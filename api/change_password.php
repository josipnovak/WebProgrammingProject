<?php

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
include "../includes/db.php";

$userId = isset($data['id']) ? intval($data['id']) : 0;
$currentPassword = isset($data['current_password']) ? $data['current_password'] : '';
$newPassword = isset($data['new_password']) ? $data['new_password'] : '';
$confirmPassword = isset($data['confirm_password']) ? $data['confirm_password'] : '';

if ($userId <= 0 || empty($currentPassword) || empty($newPassword) || $newPassword !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}
$row = $result->fetch_assoc();
if (!password_verify($currentPassword, $row['password'])) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
    exit;
}

$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $newPasswordHash, $userId);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating password']);
}

?>