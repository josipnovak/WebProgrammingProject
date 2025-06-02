<?php 
include "includes/db.php";

$stmt = $conn->prepare("SELECT id, name, capacity FROM hall");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Capacity</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['capacity']) . "</td>
                <td><a href='edit.php?type=hall&id=" . urlencode($row['id']) . "'>Click here</a></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No halls found.";
}
?>