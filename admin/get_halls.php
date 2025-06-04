<?php 
include "includes/db.php";

$halls = [];
$stmt = $conn->prepare("SELECT id, name, capacity FROM hall");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $halls[] = $row;
}
return $halls;
?>