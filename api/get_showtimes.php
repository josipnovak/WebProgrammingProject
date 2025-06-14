<?php
header('Content-Type: application/json');
include "../includes/db.php";

$showtimes = [];
$stmt = $conn->prepare("
    SELECT 
        showtime.id, 
        showtime.movie_id, 
        showtime.hall_id, 
        showtime.start_time, 
        showtime.price,
        movie.title AS movie_name,
        movie.poster_url AS poster_url
    FROM showtime
    JOIN movie ON showtime.movie_id = movie.id
    ORDER BY showtime.start_time ASC
");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $showtimes[] = $row;
}
echo json_encode($showtimes);
exit;
?>