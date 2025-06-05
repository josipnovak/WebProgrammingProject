<?php 
include '../includes/db.php';
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin.php?message=Invalid request");
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if(!$data || !isset($data['name']) || !isset($data['num_rows']) || !isset($data['seats_per_row'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}
$name = trim($data['name']);
$num_rows = intval($data['num_rows']);
$seats_per_row = intval($data['seats_per_row']);
$capacity = $num_rows * $seats_per_row;

 $stmt = $conn->prepare("INSERT INTO hall (name, capacity) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $capacity);
    if ($stmt->execute()) {
        $hall_id = $conn->insert_id;

        for ($row = 0; $row < $num_rows; $row++) {
            $row_label = chr(65 + $row); 
            for ($seat = 1; $seat <= $seats_per_row; $seat++) {
                $seat_stmt = $conn->prepare("INSERT INTO seats (hall_id, seat_row, seat_number) VALUES (?, ?, ?)");
                $seat_stmt->bind_param("isi", $hall_id, $row_label, $seat);
                $seat_stmt->execute();
            }
        }
    } else {
        echo "Error: " . $stmt->error;
    }
echo json_encode(['success' => true, 'message' => 'Hall created successfully']);
?>
