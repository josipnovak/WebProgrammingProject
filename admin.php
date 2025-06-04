<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
    <?php
    if (isset($_GET['message'])) {
        echo "<p>" . htmlspecialchars($_GET['message']) . "</p>";
    }
    ?>
    <h1>Admin Panel</h1>
    <?php include 'includes/nav.php'; ?>
    <h2>Users</h2>
    <?php 
    $users = include 'admin/get_users.php'; 
    if(!empty($users)){
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Edit</th>
                </tr>";
        foreach ($users as $row) {
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
        echo "No users found.";
    }
    ?>
    <h2>Halls</h2>
    <?php 
    $halls = include 'admin/get_halls.php'; 
    if(!empty($halls)){
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Capacity</th>
                    <th>Edit</th>
                </tr>";
        foreach ($halls as $row) {
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
    <h2>Add New Hall</h2>
    <form method="post" action="admin/add_hall.php">
        <label for="name">Hall Name:</label>
        <input type="text" name="name" id="name" required><br>
        <label for="num_rows">Number of Rows (max 26):</label>
        <input type="number" name="num_rows" id="num_rows" min="1" max="26" required><br>
        <label for="seats_per_row">Seats per Row:</label>
        <input type="number" name="seats_per_row" id="seats_per_row" min="1" required><br>
        <button type="submit">Create Hall</button>
    </form>
    <h2>Movies</h2>
    <?php 
    $movies = include 'admin/get_movies.php'; 
    if (!empty($movies)) {
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Duration</th>
                    <th>Poster Url</th>
                    <th>Edit</th>
                </tr>";
        foreach ($movies as $row) {
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
    <h2>Add New Showtime</h2>
    <form action="admin/add_showtime.php" method="post">
        <label for="movie_id">Movie:</label>
        <select name="movie_id" id="movie_id" required>
            <?php
            foreach ($movies as $movie) {
                echo "<option value=\"{$movie['id']}\">{$movie['title']}</option>";
            }
            ?>
        </select>
        <br>
        <label for="hall_id">Hall:</label>
        <select name="hall_id" id="hall_id" required>
            <?php
            foreach ($halls as $hall) {
                echo "<option value=\"{$hall['id']}\">{$hall['name']}</option>";
            }
            ?>
        </select>
        <br>
        <label for="start_time">Start Time:</label>
        <input type="textl" name="start_time" id="start_time" placeholder="dd/mm/yyyy HH:mm" required>
        <script>
            flatpickr("#start_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minDate: "today"
            });
        </script>
        <br>
        <label for="price">Price:</label>
        <input type="number" step="1" name="price" id="price" required>
        <br>
        <button type="submit">Add Showtime</button>
    </form>
</body>
</html>