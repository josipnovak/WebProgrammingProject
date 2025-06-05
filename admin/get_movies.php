<?php
header('Content-Type: application/json');
include "../includes/db.php";

$movies = [];
$stmt = $conn->prepare("SELECT id, title, duration, poster_url FROM movie");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $movies[] = $row;
}
echo json_encode($movies);
exit;
?>