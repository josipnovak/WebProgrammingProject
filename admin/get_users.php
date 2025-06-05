<?php
header('Content-Type: application/json');
include "../includes/db.php";

$users = [];
$stmt = $conn->prepare("SELECT id, username, email, role FROM users");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
echo json_encode($users);
exit;
?>