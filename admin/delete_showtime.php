<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
include '../includes/db.php';
$data = json_decode(file_get_contents('php://input'), true);
$id = isset($data['id']) ? intval($data['id']) : 0;

if ($id > 0) {
    $stmtTickets = $conn->prepare("DELETE FROM tickets WHERE showtime_id = ?");
    $stmtTickets->bind_param("i", $id);
    $stmtTickets->execute();
    $stmtTickets->close();

    $stmtShowtime = $conn->prepare("DELETE FROM showtime WHERE id = ?");
    $stmtShowtime->bind_param("i", $id);
    $stmtShowtime->execute();
    $stmtShowtime->close();

    echo json_encode(['success' => true, 'message' => 'Showtime deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid showtime ID']);
}
?>