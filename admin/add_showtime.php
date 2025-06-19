<?php
header('Content-Type: application/json');
include_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$movie_id = intval($data['movie_id'] ?? 0);
$hall_id = intval($data['hall_id'] ?? 0);
$start_time = $data['start_time'] ?? '';
$price = floatval($data['price'] ?? 0);

if (!$movie_id || !$hall_id || !$start_time || !$price) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

$movie_stmt = $conn->prepare("SELECT duration FROM movie WHERE id = ?");
$movie_stmt->bind_param("i", $movie_id);
$movie_stmt->execute();
$movie_result = $movie_stmt->get_result();
if ($movie_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid movie ID.']);
    exit;
}
$movie = $movie_result->fetch_assoc();
$duration = $movie['duration'];
$end_time = date("Y-m-d H:i:s", strtotime($start_time) + $duration * 60);

$stmt = $conn->prepare("
    SELECT COUNT(*) FROM showtime
    WHERE hall_id = ?
      AND start_time < ?
      AND end_time > ?
");
$stmt->bind_param("iss", $hall_id, $end_time, $start_time);
$stmt->execute();
$stmt->bind_result($overlap_count);
$stmt->fetch();
$stmt->close();

if ($overlap_count > 0) {
    echo json_encode(['success' => false, 'message' => 'This showtime overlaps with an existing one in the same hall.']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO showtime (movie_id, hall_id, start_time, end_time, price) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iissd", $movie_id, $hall_id, $start_time, $end_time, $price);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Showtime added successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}
exit;

?>