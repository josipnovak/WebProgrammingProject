<?php
header('Content-Type: application/json');
include "../includes/db.php";

$halls = [];
$stmt = $conn->prepare("SELECT id, name, capacity, rows_num, seats_per_row FROM hall");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $halls[] = $row;
}
echo json_encode($halls);
exit;
?>