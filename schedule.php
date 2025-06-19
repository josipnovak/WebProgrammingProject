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
            <div id="delete-msg" class="alert" style="display:none;"></div>
        </div>
        
        <div id="showtimes" class="showtime-list">
            <div class="loading">
                <div class="spinner"></div>
                <p>Loading showtimes...</p>
            </div>
        </div>

    <script>
const isAdmin = <?php echo isset($_SESSION['role']) && $_SESSION['role'] === 'admin' ? 'true' : 'false'; ?>;
console.log("Is Admin:", isAdmin);
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
                                <span><strong>Start:</strong> ${new Date(show.start_time).toLocaleDateString('en-GB', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-hotel"></i>
                                <span><strong>Hall:</strong> ${show.hall_name}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-euro-sign"></i>
                                <span><strong>Price:</strong> ${show.price}€</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-star"></i>
                                <span><strong>Rating:</strong> ${show.rating}</span>
                            </div>
                        </div>
                    </div>
                    <div class="showtime-actions">
                        ${isAdmin ? `
                        <button class="btn-danger" onclick="deleteShowtime(${show.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        ` : `<a href='buy_ticket.php?id=${show.id}' class="btn-primary">
                            <i class="fas fa-ticket-alt"></i> Buy Ticket
                        </a>`}
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

function deleteShowtime(id){
    if (!confirm("Are you sure you want to delete this showtime?")) {
        return;
    }
    const msgDiv = document.getElementById("delete-msg");
    fetch('admin/delete_showtime.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            msgDiv.textContent = data.message;
            msgDiv.style.display = 'block';
            setTimeout(() => {
                msgDiv.style.display = 'none';
            }, 2500);
            fetchShowtimes();
        } else {
            msgDiv.textContent = data.message;
            msgDiv.style.display = 'block';
            setTimeout(() => {
                msgDiv.style.display = 'none';
            }, 2500);
        }
    })
}

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

async function fetchShowtimes(filters = {}) {
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
    
    try {
        const response = await fetch("api/filter_showtimes.php", {
            method: "POST",
            body: formData
        });
        const data = await response.json();
        
        await calculateMovieRatings(data);
        renderShowtimes(data);
    } catch (error) {
        console.error('Error fetching showtimes:', error);
        showtimesDiv.innerHTML = `
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> Error loading showtimes. Please try again.
            </div>
        `;
    }
}

async function calculateMovieRatings(showtimes) {
    const ratingPromises = showtimes.map(async (show) => {
        if (show.movie_name) {
            try {
                show.rating = await getMovieRating(show.movie_name);
            } catch (error) {
                console.error(`Error getting rating for ${show.movie_name}:`, error);
                show.rating = "N/A";
            }
        } else {
            console.warn('No movie name provided for showtime:', show);
            show.rating = "N/A";
        }
    });
    
    await Promise.all(ratingPromises);
}

async function getMovieRating(title) {
    try {
        const apiKey = '6c979a3f4711b9d5d0a34501a85eced3';
        const response = await fetch(`https://api.themoviedb.org/3/search/movie?api_key=${apiKey}&query=${encodeURIComponent(title)}`);
        const data = await response.json();
       
        if (data.results && data.results.length > 0) {
            const movie = data.results[0];
            return movie.vote_average
                ? movie.vote_average.toFixed(1)
                : "N/A";
        } else {
            return "N/A";
        }
    } catch (error) {
        console.error('Error fetching movie rating:', error);
        return "N/A";
    }
}

fetchShowtimes();

flatpickr("#filter-date", {
    dateFormat: "Y-m-d",
    time_24hr: true,
    minDate: "today",
    appendTo: document.body
});
</script>
</body>
</html>