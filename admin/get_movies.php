<?php 
include "includes/db.php";

$stmt = $conn->prepare("SELECT id, title, duration, poster_url FROM movie");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>title</th>
                <th>Duration</th>
                <th>Poster Url</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['title']) . "</td>
                <td>" . htmlspecialchars($row['duration']) . "</td>
                <td>" . htmlspecialchars($row['poster_url']) . "</td>
                <td><a href='edit.php?type=movie&id=" . urlencode($row['id']) . "'>Click here</a></td>

              </tr>";
    }
    echo "</table>";
} else {
    echo "No movies found.";
}
?>