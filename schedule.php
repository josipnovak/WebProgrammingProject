<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1>Schedule</h1>
    <?php include 'includes/nav.php'; ?>
    <div class="showtime-filter">
        <input type="text" id="filter-movie" placeholder="Search movie name">
        <input type="date" id="filter-date">
        <input type="number" id="filter-price" placeholder="Max price" min="0">
        <select id="filter-hall">
                <option value="">All halls</option>
        </select>
        <button id="filter-btn">Filter</button>
        <button id="clear-filter-btn" type="button">Clear</button>
    </div>
    <div id="showtimes" class="showtime-list"></div>
    <script>

function renderShowtimes(data) {
    const showtimesDiv = document.getElementById("showtimes");
    if (data.length > 0) {
        let html = "";
        data.forEach(show => {
            html += `
                <div class="showtime-card">
                    <img src="${show.poster_url || 'images/default_poster.jpg'}"
                         alt="${show.movie_name || 'Movie'} poster"
                         onerror="this.onerror=null;this.src='images/default_poster.jpg';">
                    <h2>${show.movie_name || 'Movie Title'}</h2>
                    <div class="details">
                        <strong>Start:</strong> ${show.start_time}<br>
                        <strong>Hall:</strong> ${show.hall_id}<br>
                        <strong>Price:</strong> ${show.price} €
                    </div>
                    <a href='buy_ticket.php?id=${show.id}'>Buy Ticket</a>
                </div>
            `;
        });
        showtimesDiv.innerHTML = html;
    } else {
        showtimesDiv.innerHTML = "<p>No showtimes found.</p>";
    }
}

function fetchShowtimes(filters = {}) {
    const formData = new FormData();
    for (const key in filters) {
        formData.append(key, filters[key]);
    }
    fetch("api/filter_showtimes.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        renderShowtimes(data);
    });
}

fetchShowtimes();

document.getElementById('filter-btn').onclick = function() {
    const filters = {
        movie: document.getElementById('filter-movie').value,
        date: document.getElementById('filter-date').value,
        price: document.getElementById('filter-price').value,
        hall: document.getElementById('filter-hall').value
    };
    fetchShowtimes(filters);
};

document.getElementById('clear-filter-btn').onclick = function() {
    document.getElementById('filter-movie').value = '';
    document.getElementById('filter-date').value = '';
    document.getElementById('filter-price').value = '';
    document.getElementById('filter-hall').value = '';
    fetchShowtimes();
};
fetch("admin/get_halls.php")
    .then(res => res.json())
    .then(halls => {
        const hallSelect = document.getElementById('filter-hall');
        halls.forEach(hall => {
            const option = document.createElement('option');
            option.value = hall.id;
            option.textContent = hall.name;
            hallSelect.appendChild(option);
        });
    });
</script>
</body>
</html>