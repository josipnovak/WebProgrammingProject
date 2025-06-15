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

    $stmt2 = $conn->prepare("SELECT COUNT(*) AS ticket_count FROM tickets WHERE user_id = ?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $stmt2->bind_result($ticket_count);
    $stmt2->fetch();
    $stmt2->close();

    $stmt3 = $conn->prepare("
        SELECT COALESCE(SUM(s.price),0) AS total_spent
        FROM tickets t
        JOIN showtime s ON t.showtime_id = s.id
        WHERE t.user_id = ?
    ");
    $stmt3->bind_param("i", $id);
    $stmt3->execute();
    $stmt3->bind_result($total_spent);
    $stmt3->fetch();
    $stmt3->close();

    $stmt4 = $conn->prepare("
        SELECT COUNT(DISTINCT s.movie_id) AS movies_watched
        FROM tickets t
        JOIN showtime s ON t.showtime_id = s.id
        WHERE t.user_id = ?
    ");
    $stmt4->bind_param("i", $id);
    $stmt4->execute();
    $stmt4->bind_result($movies_watched);
    $stmt4->fetch();
    $stmt4->close();

    echo json_encode([
        'id' => $user['id'],
        'username' => htmlspecialchars($user['username']),
        'email' => htmlspecialchars($user['email']),
        'role' => htmlspecialchars($user['role']),
        'registered_at' => $user['created_at'],
        'ticket_count' => intval($ticket_count),
        'total_spent' => floatval($total_spent),
        'movies_watched' => intval($movies_watched)
    ]);
} else {
    echo json_encode(['error' => 'User not found']);
}
exit;
?>