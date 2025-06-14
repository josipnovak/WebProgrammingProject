<?php
header('Content-Type: application/json');
include_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$title= $data['title'] ?? '';
$description = $data['description'] ?? '';
$genre = $data['genre'] ?? '';
$duration = intval($data['duration'] ?? 0);
$poster_url = $data['poster_url'] ?? '';

if (!$title || !$description || !$genre || !$duration || !$poster_url) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO movie (title, description, genre, duration, poster_url) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssis", $title, $description, $genre, $duration, $poster_url);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Movie added successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}
exit;

?>