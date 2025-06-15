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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
        <h1><i class="fas fa-calendar-alt"></i> Movie Schedule</h1>
        <?php include 'includes/nav.php'; ?>
        
        <div class="showtime-filter-row">
            <h3 class="filter-title">
                <i class="fas fa-filter"></i> Filter
            </h3>
            <div class="showtime-filter">
                <input type="text" id="filter-movie" placeholder="Movie name">
                <input type="text" name="filter-date" id="filter-date" placeholder="Select date" required>
                <input type="number" id="filter-price" placeholder="Max price" min="0">
                <select id="filter-hall">
                    <option value="">Select hall</option>
                </select>
                <button id="filter-btn"><i class="fas fa-search"></i> Filter</button>
                <button id="clear-filter-btn" type="button"><i class="fas fa-times"></i> Clear</button>
            </div>
        </div>

        <div class="section-header">
            <h2><i class="fas fa-list"></i> Available Showtimes</h2>
        </div>
        
        <div id="showtimes" class="showtime-list">
            <div class="loading">
                <div class="spinner"></div>
                <p>Loading showtimes...</p>
            </div>
        </div>

    <script>

function renderShowtimes(data) {
    const showtimesDiv = document.getElementById("showtimes");
    if (data.length > 0) {
        let html = "";
        data.forEach(show => {
            html += `
                <div class="showtime-card">
                    <div class="showtime-poster">
                        <img src="${show.poster_url || 'images/default_poster.jpg'}"
                             alt="${show.movie_name || 'Movie'} poster"
                             onerror="this.onerror=null;this.src='images/default_poster.jpg';">
                    </div>
                    <div class="showtime-info">
                        <h3><i class="fas fa-film"></i> ${show.movie_name || 'Movie Title'}</h3>
                        <div class="showtime-details">
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span><strong>Start:</strong> ${show.start_time}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-hotel"></i>
                                <span><strong>Hall:</strong> ${show.hall_name}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-euro-sign"></i>
                                <span><strong>Price:</strong> ${show.price}€</span>
                            </div>
                        </div>
                    </div>
                    <div class="showtime-actions">
                        <a href='buy_ticket.php?id=${show.id}' class="btn-primary">
                            <i class="fas fa-ticket-alt"></i> Buy Ticket
                        </a>
                    </div>
                </div>
            `;
        });
        showtimesDiv.innerHTML = html;
    } else {
        showtimesDiv.innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No showtimes found for selected filter.
            </div>
        `;
    }
}

function fetchShowtimes(filters = {}) {
    const showtimesDiv = document.getElementById("showtimes");
    showtimesDiv.innerHTML = `
        <div class="loading">
            <div class="spinner"></div>
            <p>Loading showtimes...</p>
        </div>
    `;
    
    const formData = new FormData();
    for (const key in filters) {
        formData.append(key, filters[key]);
    }
    console.log("Fetching showtimes with filters:", Object.fromEntries(formData.entries()));
    fetch("api/filter_showtimes.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        renderShowtimes(data);
    })
    .catch(error => {
        showtimesDiv.innerHTML = `
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> Error loading showtimes. Please try again.
            </div>
        `;
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
    })
    .catch(error => {
        console.error('Error loading halls:', error);
    });

flatpickr("#filter-date", {
    dateFormat: "Y-m-d",
    time_24hr: true,
    minDate: "today",
    appendTo: document.body
});
</script>
</body>
</html>