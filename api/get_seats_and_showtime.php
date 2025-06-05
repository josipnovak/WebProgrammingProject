<?php
session_start();
header('Content-Type: application/json');
include '../includes/db.php';

$showtime_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT s.*, m.title, h.name AS hall_name, h.id AS hall_id
                        FROM showtime s 
                        JOIN movie m ON s.movie_id = m.id 
                        JOIN hall h ON s.hall_id = h.id 
                        WHERE s.id = ?");
$stmt->bind_param("i", $showtime_id);
$stmt->execute();
$result = $stmt->get_result();
$showtime = $result->fetch_assoc();

$seats = [];
if ($showtime) {
    $seat_stmt = $conn->prepare("SELECT * FROM seats WHERE hall_id = ? ORDER BY seat_row, seat_number");
    $seat_stmt->bind_param("i", $showtime['hall_id']);
    $seat_stmt->execute();
    $seat_result = $seat_stmt->get_result();
    while ($row = $seat_result->fetch_assoc()) {
        $seats[$row['seat_row']][$row['seat_number']] = $row;
    }
}

$reserved = [];
if ($showtime) {
    $res_stmt = $conn->prepare("SELECT seat_id FROM tickets WHERE showtime_id = ?");
    $res_stmt->bind_param("i", $showtime_id);
    $res_stmt->execute();
    $res_result = $res_stmt->get_result();
    while ($row = $res_result->fetch_assoc()) {
        $reserved[$row['seat_id']] = true;
    }
}

echo json_encode([
    'showtime' => $showtime,
    'seats' => $seats,
    'reserved' => $reserved
]);