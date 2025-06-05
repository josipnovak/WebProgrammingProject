<?php
header('Content-Type: application/json');
include "../includes/db.php";

$data = json_decode(file_get_contents('php://input'), true);

$id = isset($data['id']) ? intval($data['id']) : 0;

if ($id <= 0) {
    echo json_encode(['error' => 'Invalid user ID']);
    exit;
}
$stmt = $conn->prepare("SELECT id, username, email, role, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        'id' => $user['id'],
        'username' => htmlspecialchars($user['username']),
        'email' => htmlspecialchars($user['email']),
        'role' => htmlspecialchars($user['role']),
        'registered_at' => $user['created_at']
    ]);
} else {
    echo json_encode(['error' => 'User not found']);
}
exit;
?>