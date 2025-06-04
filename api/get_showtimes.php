<?php 
include "includes/db.php";

$showtimes = [];
$stmt = $conn->prepare("SELECT id, movie_id, hall_id, start_time, price FROM showtime ORDER BY start_time ASC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $showtimes[] = $row;
}
return $showtimes;
?>
