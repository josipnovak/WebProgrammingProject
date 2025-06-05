<?php
session_start();
header('Content-Type: application/json');
include '../includes/db.php';

$data = json_decode(file_get_contents('php://input'), true);

$user_id = $_SESSION['id'] ?? null;
$showtime_id = $data['showtime_id'] ?? 0;
$seat_ids = $data['seat_ids'] ?? [];

if (!$user_id || !$showtime_id || empty($seat_ids)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit;
}

$success = 0;
$failed = 0;

foreach ($seat_ids as $seat_id) {
    $seat_id = intval($seat_id);
    $stmt = $conn->prepare("SELECT id FROM tickets WHERE showtime_id = ? AND seat_id = ?");
    $stmt->bind_param("ii", $showtime_id, $seat_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $failed++;
        continue;
    }

    $insert = $conn->prepare("INSERT INTO tickets (user_id, showtime_id, seat_id) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $user_id, $showtime_id, $seat_id);
    if ($insert->execute()) {
        $success++;
    } else {
        $failed++;
    }
}

echo json_encode([
    'success' => $success > 0,
    'message' => "$success ticket(s) purchased. $failed failed."
]);
?>