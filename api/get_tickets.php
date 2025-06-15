<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
include "../includes/db.php";

$userId = isset($data['id']) ? intval($data['id']) : 0;

$stmt = $conn->prepare("SELECT 
        t.id, 
        t.showtime_id, 
        t.seat_id, 
        m.title, 
        m.poster_url, 
        se.seat_row, 
        se.seat_number,
        s.start_time,
        h.name
    FROM tickets t
    JOIN showtime s ON t.showtime_id = s.id
    JOIN movie m ON s.movie_id = m.id
    JOIN seats se ON t.seat_id = se.id
    JOIN hall h ON s.hall_id = h.id
    WHERE t.user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$tickets = [];
while ($row = $result->fetch_assoc()) {
    $tickets[] = [
        'id' => $row['id'],
        'movie_title' => htmlspecialchars($row['title']),
        'seat_row' => htmlspecialchars($row['seat_row']),
        'seat_number' => htmlspecialchars($row['seat_number']),
        'poster_url' => $row['poster_url'] ? htmlspecialchars($row['poster_url']) : 'images/default_poster.jpg',
        'start_time' => date('Y-m-d H:i', strtotime($row['start_time'])),
        'hall_name' => htmlspecialchars($row['name']),
    ];
}
echo json_encode($tickets);
?>