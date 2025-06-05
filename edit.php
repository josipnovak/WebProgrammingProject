<?php
session_start();
include "includes/db.php";

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? '';

if (!$type || !$id) {
    echo "Invalid request.";
    exit;
}

$table = '';
$fields = [];
$labels = [];

switch ($type) {
    case 'user':
        $table = 'users';
        $fields = ['username', 'email', 'role'];
        $labels = ['Username', 'Email', 'Role'];
        break;
    case 'hall':
        $table = 'hall';
        $fields = ['name', 'capacity'];
        $labels = ['Name', 'Capacity'];
        break;
    case 'movie':
        $table = 'movie';
        $fields = ['title', 'description', 'duration', 'genre', 'poster_url'];
        $labels = ['Title', 'Description', 'Duration', 'Genre', 'Poster URL'];
        break;
    default:
        echo "Unknown type.";
        exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    $updates = [];
    $params = [];
    $types = '';
    foreach ($fields as $field) {
        $updates[] = "$field = ?";
        $params[] = $data[$field] ?? '';
        $types .= 's';
    }
    $params[] = $id;
    $types .= 'i';

    $sql = "UPDATE $table SET " . implode(', ', $updates) . " WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update record']);
    }
    exit;
}

$stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "Record not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit <?php echo htmlspecialchars($type); ?></title>
</head>
<body>
    <h1>Edit <?php echo ucfirst(htmlspecialchars($type)); ?></h1>
    <?php include 'includes/nav.php'?>
    <form id="edit-form">
        <?php foreach ($fields as $i => $field): ?>
            <label><?php echo $labels[$i]; ?>:</label>
            <input type="text" name="<?php echo $field; ?>" value="<?php echo htmlspecialchars($data[$field]); ?>" required><br><br>
        <?php endforeach; ?>
        <button type="submit">Save</button>
        <span id="edit-msg"></span>
    </form>
    <a href="admin.php">Back to Admin Panel</a>
    <script>
    document.getElementById('edit-form').onsubmit = function(e) {
        e.preventDefault();
        const form = e.target;
        const payload = {};
        <?php foreach ($fields as $field): ?>
        payload["<?php echo $field; ?>"] = form["<?php echo $field; ?>"].value;
        <?php endforeach; ?>
        fetch(window.location.href, {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('edit-msg').textContent = data.message;
            if(data.success) {
                setTimeout(() => window.location.href = 'admin.php?message=' + encodeURIComponent(data.message), 1000);
            }
        });
    };
    </script>
</body>
</html>