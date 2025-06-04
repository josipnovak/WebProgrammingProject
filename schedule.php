<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
</head>
<body>
    <h1>Schedule</h1>
    <?php include 'includes/nav.php'; ?>
    <?php
        $showtimes = include 'api/get_showtimes.php';
        if ($showtimes && count($showtimes) > 0) {
            echo "<table border='1'><tr><th>ID</th><th>Movie ID</th><th>Hall ID</th><th>Start Time</th><th>Price</th><th>Buy ticket</th></tr>";
            foreach ($showtimes as $show) {
                $movie_stmt = $conn->prepare("SELECT title FROM movie WHERE id = ?");
                $movie_stmt->bind_param("i", $show['movie_id']);
                $movie_stmt->execute();
                $movie_result = $movie_stmt->get_result();
                $movie = $movie_result->fetch_assoc();
                echo "<tr>";
                echo "<td>" . htmlspecialchars($show['id']) . "</td>";
                echo "<td>" . htmlspecialchars($show['movie_id']) . "</td>";
                echo "<td>" . htmlspecialchars($show['hall_id']) . "</td>";
                echo "<td>" . htmlspecialchars($show['start_time']) . "</td>";
                echo "<td>" . htmlspecialchars($show['price']) . "</td>";
                echo "<td><a href='buy_ticket.php?showtime_id=" . urlencode($show['id']) . "'>Buy Ticket</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No showtimes found.</p>";
        }
    ?>
</body>
</html>