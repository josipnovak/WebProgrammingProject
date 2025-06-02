<?php 
include "includes/db.php";

$stmt = $conn->prepare("SELECT id, movie_id, hall_id, start_time, price FROM showtime");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Movie_id</th>
                <th>Hall_id</th>
                <th>Start time</th>
                <th>Price</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['movie_id']) . "</td>
                <td>" . htmlspecialchars($row['hall_id']) . "</td>
                <td>" . htmlspecialchars($row['start_time']) . "</td>
                <td>" . htmlspecialchars($row['price']) . "</td>
                <td><a href='edit.php?type=hall&id=" . urlencode($row['id']) . "'>Click here</a></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No showtimes found.";
}
?>