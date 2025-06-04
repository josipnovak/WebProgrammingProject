<?php
include_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = intval($_POST['movie_id']);
    $hall_id = intval($_POST['hall_id']);
    $start_time = $_POST['start_time'];
    $price = floatval($_POST['price']);

    $movie_stmt = $conn->prepare("SELECT duration FROM movie WHERE id = ?");
    $movie_stmt->bind_param("i", $movie_id);
    $movie_stmt->execute();
    $movie_result = $movie_stmt->get_result();
    if ($movie_result->num_rows === 0) {
        echo "Invalid movie ID.";
        exit;
    }
    $movie = $movie_result->fetch_assoc();
    $duration = $movie['duration'];
    $end_time = date("Y-m-d H:i:s", strtotime($start_time) + $duration * 60);

    $stmt = $conn->prepare("INSERT INTO showtime (movie_id, hall_id, start_time, end_time, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissd", $movie_id, $hall_id, $start_time, $end_time, $price);

    if ($stmt->execute()) {
        header("Location: ../admin.php?message=Showtime added successfully");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>