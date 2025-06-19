<?php
session_start();
header('Content-Type: application/json');
include "../includes/db.php";

$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

$movie = trim($_POST['movie'] ?? '');
$date = trim($_POST['date'] ?? '');
$price = trim($_POST['price'] ?? '');
$hall = trim($_POST['hall'] ?? '');

if ($isAdmin) {
    $query = "
    SELECT 
        showtime.id, 
        showtime.movie_id, 
        showtime.hall_id, 
        showtime.start_time, 
        showtime.price,
        movie.title AS movie_name,
        movie.poster_url AS poster_url,
        hall.name AS hall_name
    FROM showtime
    JOIN movie ON showtime.movie_id = movie.id
    JOIN hall ON showtime.hall_id = hall.id
";
}
else{
    $query = "
        SELECT 
            showtime.id, 
            showtime.movie_id, 
            showtime.hall_id, 
            showtime.start_time, 
            showtime.price,
            movie.title AS movie_name,
            movie.poster_url AS poster_url,
            hall.name AS hall_name
        FROM showtime
        JOIN movie ON showtime.movie_id = movie.id
        JOIN hall ON showtime.hall_id = hall.id
        WHERE showtime.start_time >= NOW()
    ";
}
$params = [];
$types = "";

if ($movie !== "") {
    $query .= " AND movie.title LIKE ?";
    $params[] = "%$movie%";
    $types .= "s";
}
if ($date !== "") {
    $query .= " AND DATE(showtime.start_time) = ?";
    $params[] = $date;
    $types .= "s";
}
if ($price !== "") {
    $query .= " AND showtime.price <= ?";
    $params[] = $price;
    $types .= "d";
}
if ($hall !== "") {
    $query .= " AND showtime.hall_id LIKE ?";
    $params[] = "%$hall%";
    $types .= "s";
}
$query .= " ORDER BY showtime.start_time ASC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$showtimes = [];
while ($row = $result->fetch_assoc()) {
    $showtimes[] = $row;
}
echo json_encode($showtimes);
exit;
?>