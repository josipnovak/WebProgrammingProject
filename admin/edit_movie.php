<?php
session_start();
include "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$movie_id = intval($data['movie_id'] ?? 0);
$title = $data['title'] ?? '';
$description = $data['description'] ?? '';
$genre = $data['genre'] ?? '';
$duration = intval($data['duration'] ?? 0);
$poster_url = $data['poster_url'] ?? '';

if (!$movie_id || !$title || !$description || !$genre || !$duration || !$poster_url) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

$stmt = $conn->prepare("UPDATE movie SET title = ?, description = ?, genre = ?, duration = ?, poster_url = ? WHERE id = ?");
$stmt->bind_param("sssisi", $title, $description, $genre, $duration, $poster_url, $movie_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}

?>