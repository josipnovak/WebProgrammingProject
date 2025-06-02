<?php 
include "includes/db.php";

$stmt = $conn->prepare("SELECT id, username, email, role FROM users");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['username']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['role']) . "</td>
                <td><a href='edit.php?type=user&id=" . urlencode($row['id']) . "'>Click here</a></td>

              </tr>";
    }
    echo "</table>";
} else {
    echo "Nema korisnika.";
}
?>