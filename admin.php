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
    <style>
        table { border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 4px 8px; }
        th { background: #eee; }
    </style>
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
    <div id="users-table">Loading...</div>

    <h2>Halls</h2>
    <div id="halls-table">Loading...</div>

    <h2>Add New Hall</h2>
    <form id="add-hall-form">
        <label for="name">Hall Name:</label>
        <input type="text" name="name" id="name" required><br>
        <label for="num_rows">Number of Rows (max 26):</label>
        <input type="number" name="num_rows" id="num_rows" min="1" max="26" required><br>
        <label for="seats_per_row">Seats per Row:</label>
        <input type="number" name="seats_per_row" id="seats_per_row" min="1" required><br>
        <button type="submit">Create Hall</button>
        <span id="add-hall-msg"></span>
    </form>

    <h2>Movies</h2>
    <div id="movies-table">Loading...</div>

    <h2>Add New Showtime</h2>
    <form id="add-showtime-form">
        <label for="movie_id">Movie:</label>
        <select name="movie_id" id="movie_id" required></select>
        <br>
        <label for="hall_id">Hall:</label>
        <select name="hall_id" id="hall_id" required></select>
        <br>
        <label for="start_time">Start Time:</label>
        <input type="text" name="start_time" id="start_time" placeholder="dd/mm/yyyy HH:mm" required>
        <br>
        <label for="price">Price:</label>
        <input type="number" step="1" name="price" id="price" required>
        <br>
        <button type="submit">Add Showtime</button>
        <span id="add-showtime-msg"></span>
    </form>

    <script>
    fetch('admin/get_users.php')
        .then(res => res.json())
        .then(users => {
            let html = '';
            if (users.length) {
                html += `<table><tr>
                    <th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Edit</th>
                </tr>`;
                users.forEach(u => {
                    html += `<tr>
                        <td>${u.id}</td>
                        <td>${u.username}</td>
                        <td>${u.email}</td>
                        <td>${u.role}</td>
                        <td><a href="edit.php?type=user&id=${encodeURIComponent(u.id)}">Click here</a></td>
                    </tr>`;
                });
                html += `</table>`;
            } else {
                html = "No users found.";
            }
            document.getElementById('users-table').innerHTML = html;
        });

    fetch('admin/get_halls.php')
        .then(res => res.json())
        .then(halls => {
            let html = '';
            if (halls.length) {
                html += `<table><tr>
                    <th>ID</th><th>Name</th><th>Capacity</th><th>Edit</th>
                </tr>`;
                halls.forEach(h => {
                    html += `<tr>
                        <td>${h.id}</td>
                        <td>${h.name}</td>
                        <td>${h.capacity}</td>
                        <td><a href="edit.php?type=hall&id=${encodeURIComponent(h.id)}">Click here</a></td>
                    </tr>`;
                });
                html += `</table>`;
            } else {
                html = "No halls found.";
            }
            document.getElementById('halls-table').innerHTML = html;

            const hallSelect = document.getElementById('hall_id');
            hallSelect.innerHTML = halls.map(h => `<option value="${h.id}">${h.name}</option>`).join('');
        });

    fetch('admin/get_movies.php')
        .then(res => res.json())
        .then(movies => {
            let html = '';
            if (movies.length) {
                html += `<table><tr>
                    <th>ID</th><th>Title</th><th>Duration</th><th>Poster Url</th><th>Edit</th>
                </tr>`;
                movies.forEach(m => {
                    html += `<tr>
                        <td>${m.id}</td>
                        <td>${m.title}</td>
                        <td>${m.duration}</td>
                        <td>${m.poster_url}</td>
                        <td><a href="edit.php?type=movie&id=${encodeURIComponent(m.id)}">Click here</a></td>
                    </tr>`;
                });
                html += `</table>`;
            } else {
                html = "No movies found.";
            }
            document.getElementById('movies-table').innerHTML = html;

            const movieSelect = document.getElementById('movie_id');
            movieSelect.innerHTML = movies.map(m => `<option value="${m.id}">${m.title}</option>`).join('');
        });

    document.getElementById('add-hall-form').onsubmit = function(e) {
        e.preventDefault();
        const name = document.getElementById('name').value;
        const num_rows = document.getElementById('num_rows').value;
        const seats_per_row = document.getElementById('seats_per_row').value;
        fetch('admin/add_hall.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({name, num_rows, seats_per_row})
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('add-hall-msg').textContent = data.message;
            if(data.success) location.reload();
        });
    };

    document.getElementById('add-showtime-form').onsubmit = function(e) {
        e.preventDefault();
        const movie_id = document.getElementById('movie_id').value;
        const hall_id = document.getElementById('hall_id').value;
        const start_time = document.getElementById('start_time').value;
        const price = document.getElementById('price').value;
        fetch('admin/add_showtime.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({movie_id, hall_id, start_time, price})
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('add-showtime-msg').textContent = data.message;
            if(data.success) location.reload();
        });
    };

    flatpickr("#start_time", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        minDate: "today"
    });
    </script>
</body>
</html>