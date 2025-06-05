<?php
include '../includes/db.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$confirm_password = $data['confirm_password'] ?? '';

if (!$username || !$email || !$password || !$confirm_password) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
if ($password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'User with this username or email already exists.']);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hashed_password);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Registration successful! You can now log in.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error during registration. Please try again.']);
}
$stmt->close();
$conn->close();
?>