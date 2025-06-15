<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Admin Dashboard</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
        <?php
        if (isset($_GET['message'])) {
            echo '<div class="alert alert-info"><i class="fas fa-info-circle"></i> ' . htmlspecialchars($_GET['message']) . '</div>';
        }
        ?>
        
        <h1>Admin Dashboard</h1>
        <?php include 'includes/nav.php'; ?>

        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon movies">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3 id="total-users">0</h3>
                    <p>Total Users</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon movies">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-info">
                    <h3 id="total-halls">0</h3>
                    <p>Cinema Halls</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon movies">
                    <i class="fas fa-film"></i>
                </div>
                <div class="stat-info">
                    <h3 id="total-movies">0</h3>
                    <p>Movies</p>
                </div>
            </div>
        </div>

        <div class="admin-tabs">
            <button class="tab-btn active" onclick="openTab(event, 'users-tab')">
                <i class="fas fa-users"></i> Users
            </button>
            <button class="tab-btn" onclick="openTab(event, 'halls-tab')">
                <i class="fas fa-building"></i> Halls
            </button>
            <button class="tab-btn" onclick="openTab(event, 'movies-tab')">
                <i class="fas fa-film"></i> Movies
            </button>
            <button class="tab-btn" onclick="openTab(event, 'showtimes-tab')">
                <i class="fas fa-plus-circle"></i> Add Showtime
            </button>
        </div>
        <div style="display: flex; gap: 30px;" id="admin-layout">
            <div id="main-content" style="flex: 2;">
            <div id="users-tab" class="tab-content active">
                <div class="section-header">
                    <h2><i class="fas fa-users"></i> User Management</h2>
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" id="users-search" placeholder="Search users...">
                    </div>
                </div>
                <div class="table-container">
                    <div id="users-table" class="loading">
                        <div class="spinner"></div>
                        <p>Loading users...</p>
                    </div>
                </div>
            </div>

            <div id="halls-tab" class="tab-content">
                <div class="section-header">
                    <h2><i class="fas fa-building"></i> Hall Management</h2>
                    <button class="add-btn" onclick="toggleForm('add-hall-form')">
                        <i class="fas fa-plus"></i> Add New Hall
                    </button>
                </div>
                
                <div id="add-hall-form" class="form-container hidden">
                    <div class="form-card">
                        <h3><i class="fas fa-plus-circle"></i> Create New Hall</h3>
                        <form id="hall-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name"><i class="fas fa-tag"></i> Hall Name</label>
                                    <input type="text" name="name" id="name" placeholder="Enter hall name" required>
                                </div>
                                <div class="form-group">
                                    <label for="num_rows"><i class="fas fa-list-ol"></i> Number of Rows</label>
                                    <input type="number" name="num_rows" id="num_rows" min="1" max="10" placeholder="Max 10" required>
                                </div>
                                <div class="form-group">
                                    <label for="seats_per_row"><i class="fas fa-chair"></i> Seats per Row</label>
                                    <input type="number" name="seats_per_row" id="seats_per_row" min="1" placeholder="Enter number" required>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save"></i> Create Hall
                                </button>
                                <button type="button" onclick="toggleForm('add-hall-form')" class="btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                            <div id="add-hall-msg" class="form-message"></div>
                        </form>
                    </div>
                </div>

                <div class="table-container">
                    <div id="halls-table" class="loading">
                        <div class="spinner"></div>
                        <p>Loading halls...</p>
                    </div>
                </div>
            </div>

            <div id="movies-tab" class="tab-content">
                <div class="section-header">
                    <h2><i class="fas fa-film"></i> Movie Management</h2>
                    <button class="add-btn" onclick="toggleForm('add-movie-form')">
                        <i class="fas fa-plus"></i> Add New Movie
                    </button>
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" id="movies-search" placeholder="Search movies...">
                    </div>
                </div>
                <div id="add-movie-form" class="form-container hidden">
                    <div class="form-card">
                        <h3><i class="fas fa-plus-circle"></i> Add new movie</h3>
                        <form id="movie-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="title"><i class="fas fa-tag"></i> Movie Title</label>
                                    <input type="text" name="title" id="title" placeholder="Enter movie title" required>
                                </div>
                                <div class="form-group">
                                    <label for="description"><i class="fas fa-list-ol"></i> Description</label>
                                    <input type="text" name="description" id="description" placeholder="Enter movie description" required>
                                </div>
                                <div class="form-group">
                                    <label for="duration"><i class="fas fa-clock"></i> Duration</label>
                                    <input type="number" name="duration" id="duration" min="1" placeholder="Enter duration" required>
                                </div>
                                <div class="form-group">
                                    <label for="genre"><i class="fas fa-face"></i> Genre</label>
                                    <input type="text" name="genre" id="genre" placeholder="Enter movie genre" required>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save"></i> Add movie
                                </button>
                                <button type="button" onclick="toggleForm('add-movie-form')" class="btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                            <div id="add-movie-msg" class="form-message"></div>
                        </form>
                    </div>
                </div>
                <div class="table-container">
                    <div id="movies-table" class="loading">
                        <div class="spinner"></div>
                        <p>Loading movies...</p>
                    </div>
                </div>
                
            </div>

            <div id="showtimes-tab" class="tab-content">
                <div class="section-header">
                    <h2><i class="fas fa-plus-circle"></i> Add New Showtime</h2>
                </div>
                
                <div class="form-container">
                    <div class="form-card">
                        <form id="showtime-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="movie_id"><i class="fas fa-film"></i> Select Movie</label>
                                    <select name="movie_id" id="movie_id" required>
                                        <option value="">Choose a movie...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="hall_id"><i class="fas fa-building"></i> Select Hall</label>
                                    <select name="hall_id" id="hall_id" required>
                                        <option value="">Choose a hall...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="start_time"><i class="fas fa-calendar-alt"></i> Start Date & Time</label>
                                    <input type="text" name="start_time" id="start_time" placeholder="Select date and time" required>
                                </div>
                                <div class="form-group">
                                    <label for="price"><i class="fas fa-euro-sign"></i> Ticket Price</label>
                                    <input type="number" step="1" min="0" max="200" name="price" id="price" placeholder="Enter price" required>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-plus"></i> Add Showtime
                                </button>
                            </div>
                            <div id="add-showtime-msg" class="form-message"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
        closeEditForm(); 
    }

    function toggleForm(formId) {
        const form = document.getElementById(formId);
        form.classList.toggle('hidden');
    }

    function filterTable(searchInput, tableId) {
        const filter = searchInput.value.toLowerCase();
        const table = document.getElementById(tableId);
        const rows = table.getElementsByTagName('tr');
        
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            let found = false;
            
            for (let j = 0; j < cells.length - 1; j++) {
                if (cells[j].textContent.toLowerCase().includes(filter)) {
                    found = true;
                    break;
                }
            }
            
            row.style.display = found ? '' : 'none';
        }
    }
        function fetchUsers(){
            fetch('admin/get_users.php')
                .then(res => res.json())
                .then(users => {
                    document.getElementById('total-users').textContent = users.length;
                    let html = '';
                    if (users.length) {
                        html = `<table class="admin-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user"></i> Username</th>
                                    <th><i class="fas fa-envelope"></i> Email</th>
                                    <th><i class="fas fa-user-tag"></i> Role</th>
                                    <th><i class="fas fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>`;
                        users.forEach(u => {
                            let user = JSON.stringify(u).replace(/'/g, "\\'");
                            html += `<tr>
                                <td><strong>${u.username}</strong></td>
                                <td>${u.email}</td>
                                <td><span class="role-badge ${u.role}">${u.role}</span></td>
                                <td>
                                    <a href="#" class="action-btn edit" onclick='editUser(${user}); return false;'>
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>`;
                        });
                        html += `</tbody></table>`;
                    } else {
                        html = '<div class="no-data"><i class="fas fa-users"></i><p>No users found</p></div>';
                    }
                    document.getElementById('users-table').innerHTML = html;
                });
        }

        function editUser(user) {
            let container = document.getElementById('edit-form-container');
            
            if (!container) {
                container = document.createElement('div');
                container.id = 'edit-form-container';
                container.style.flex = '0 0 400px'; 
                container.style.maxWidth = '400px';
                container.className = 'form-container'; 
                document.getElementById('admin-layout').prepend(container);
            }

            container.innerHTML = `
                <div class="form-card">
                    <h3><i class="fas fa-edit"></i> Edit User</h3>
                    <form id="edit-user-form">
                    <input type="hidden" name="user_id" value="${user.id}">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" value="${user.username}" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role">
                                <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                                <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Save</button>
                            <button type="button" class="btn-secondary" onclick="closeEditForm()">Cancel</button>
                        </div>
                        <div id="edit-user-msg" class="form-message"></div>
                    </form>
                </div>
            `;
            document.getElementById("edit-user-form").onsubmit = function(e) {
                e.preventDefault();
                const user_id = document.querySelector('#edit-user-form input[name="user_id"]').value;
                const username = document.querySelector('#edit-user-form input[name="username"]').value;
                const role = document.querySelector('#edit-user-form select[name="role"]').value;

                const msgEl = document.getElementById('edit-user-msg');

                fetch('admin/edit_user.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({user_id, username, role})
                })
                .then(res => res.json())
                .then(data => {
                    msgEl.innerHTML = `<div class="alert ${data.success ? 'alert-success' : 'alert-error'}">
                        <i class="fas ${data.success ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${data.message}`
                    if (data.success) {
                        closeEditForm();
                        fetchUsers(); 
                    } else {
                        msgEl.innerHTML = `<div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> ${data.message}
                        </div>`;
                    }
                });
            };
        }

    function fetchHalls(){
        fetch('admin/get_halls.php')
            .then(res => res.json())
            .then(halls => {
                document.getElementById('total-halls').textContent = halls.length;
                let html = '';
                if (halls.length) {
                    html = `<table class="admin-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-building"></i> Hall Name</th>
                                <th><i class="fas fa-users"></i> Capacity</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    halls.forEach(h => {
                        let hall = JSON.stringify(h).replace(/'/g, "\\'");
                        html += `<tr>
                            <td><strong>${h.name}</strong></td>
                            <td><span class="capacity-badge">${h.capacity} seats</span></td>
                            <td>
                                <a href="#" class="action-btn edit" onclick='editHall(${hall}); return false;'>
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                </td>
                        </tr>`;
                    });
                    html += `</tbody></table>`;
                } else {
                    html = '<div class="no-data"><i class="fas fa-building"></i><p>No halls found</p></div>';
                }
                document.getElementById('halls-table').innerHTML = html;

                const hallSelect = document.getElementById('hall_id');
                hallSelect.innerHTML = '<option value="">Choose a hall...</option>' + 
                    halls.map(h => `<option value="${h.id}">${h.name} (${h.capacity} seats)</option>`).join('');
            });
    }

    function editHall(hall) {
        let container = document.getElementById('edit-form-container');
        if(container)
            container.remove();

        if (!container) {
            container = document.createElement('div');
            container.id = 'edit-form-container';
            container.style.flex = '0 0 400px'; 
            container.style.maxWidth = '400px';
            container.className = 'form-container'; 
            document.getElementById('admin-layout').prepend(container);
        }
        container.innerHTML = `
            <div class="form-card">
                <h3><i class="fas fa-edit"></i> Edit Hall</h3>
                <form id="edit-hall-form">
                    <input type="hidden" name="hall_id" value="${hall.id}">
                    <div class="form-group">
                        <label>Hall Name</label>
                        <input type="text" name="name" value="${hall.name}" required>
                    </div>
                    <div class="form-actions">
                            <button type="submit" class="btn-primary">Save</button>
                            <button type="button" class="btn-secondary" onclick="closeEditForm()">Cancel</button>
                        </div>
                    <div id="edit-hall-msg" class="form-message"></div>
                    </form>
            </div>`;
        document.getElementById("edit-hall-form").onsubmit = function(e) {
            e.preventDefault();
            const hall_id = document.querySelector('#edit-hall-form input[name="hall_id"]').value;
            const name = document.querySelector('#edit-hall-form input[name="name"]').value;
            const msgEl = document.getElementById('edit-hall-msg');
            fetch('admin/edit_hall.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({hall_id, name})
            })
                .then(res => res.json())
                .then(data => {
                    msgEl.innerHTML = `<div class="alert ${data.success ? 'alert-success' : 'alert-error'}">
                        <i class="fas ${data.success ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${data.message}`;
                    if (data.success) {
                        closeEditForm();
                        fetchHalls(); 
                    } else {
                        msgEl.innerHTML = `<div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> ${data.message}
                        </div>`;
                    }
                });
        }
        
    }
    function fetchMovies() {
        fetch('admin/get_movies.php')
            .then(res => res.json())
            .then(movies => {
                document.getElementById('total-movies').textContent = movies.length;
                let html = '';
                if (movies.length) {
                    html = `<table class="admin-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-image"></i> Poster</th>
                                <th><i class="fas fa-film"></i> Title</th>
                                <th><i class="fas fa-clock"></i> Duration</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    movies.forEach(m => {
                        let movie = JSON.stringify(m).replace(/'/g, "\\'");
                        html += `<tr>
                            <td><img src="${m.poster_url}" alt="${m.title}" class="movie-thumb"></td>
                            <td><strong>${m.title}</strong></td>
                            <td><span class="duration-badge">${m.duration} min</span></td>
                            <td><a href="#" class="action-btn edit" onclick='editMovie(${movie}); return false;'>
                                <i class="fas fa-edit"></i> Edit
                            </a></td>
                        </tr>`;
                    });
                    html += `</tbody></table>`;
                } else {
                    html = '<div class="no-data"><i class="fas fa-film"></i><p>No movies found</p></div>';
                }
                document.getElementById('movies-table').innerHTML = html;

                const movieSelect = document.getElementById('movie_id');
                movieSelect.innerHTML = '<option value="">Choose a movie...</option>' + 
                    movies.map(m => `<option value="${m.id}">${m.title} (${m.duration} min)</option>`).join('');
            });
    }

    function editMovie(movie) {
        let container = document.getElementById('edit-form-container');
        if(container)
            container.remove();

        if (!container) {
            container = document.createElement('div');
            container.id = 'edit-form-container';
            container.style.flex = '0 0 400px'; 
            container.style.maxWidth = '400px';
            container.className = 'form-container'; 
            document.getElementById('admin-layout').prepend(container);
        }
        container.innerHTML = `
            <div class="form-card">
                <h3><i class="fas fa-edit"></i> Edit Movie</h3>
                <form id="edit-movie-form">
                    <input type="hidden" name="movie_id" value="${movie.id}">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" value="${movie.title}" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" name="description" value="${movie.description}" required>
                    </div>
                    <div class="form-group">
                        <label>Genre</label>
                        <input type="text" name="genre" value="${movie.genre}" required>
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="number" name="duration" value="${movie.duration}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Poster URL</label>
                        <input type="text" name="poster_url" value="${movie.poster_url}" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Save</button>
                        <button type="button" class="btn-secondary" onclick="closeEditForm()">Cancel</button>
                    </div>
                    <div id="edit-movie-msg" class="form-message"></div>
                </form>
            </div>`;
        document.getElementById("edit-movie-form").onsubmit = function(e) {
            e.preventDefault();
            const movie_id = document.querySelector('#edit-movie-form input[name="movie_id"]').value;
            const title = document.querySelector('#edit-movie-form input[name="title"]').value;
            const description = document.querySelector('#edit-movie-form input[name="description"]').value;
            const genre = document.querySelector('#edit-movie-form input[name="genre"]').value;
            const duration = document.querySelector('#edit-movie-form input[name="duration"]').value;
            const poster_url = document.querySelector('#edit-movie-form input[name="poster_url"]').value;

            const msgEl = document.getElementById('edit-movie-msg');

            fetch('admin/edit_movie.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({movie_id, title, description, genre, duration, poster_url})
            })
                .then(res => res.json())
                .then(data => {
                    msgEl.innerHTML = `<div class="alert ${data.success ? 'alert-success' : 'alert-error'}">
                        <i class="fas ${data.success ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${data.message}`;
                    if (data.success) {
                        closeEditForm();
                        fetchMovies(); 
                    } else {
                        msgEl.innerHTML = `<div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> ${data.message}
                        </div>`;
                    }
                });
        }
    }

    function closeEditForm() {
        const container = document.getElementById('edit-form-container');
        if (container) {
            container.remove();
        }
    }

    document.getElementById('users-search').addEventListener('input', function() {
        filterTable(this, 'users-table');
    });

    document.getElementById('movies-search').addEventListener('input', function() {
        filterTable(this, 'movies-table');
    });

    document.getElementById('hall-form').onsubmit = function(e) {
        e.preventDefault();
        const name = document.getElementById('name').value;
        const num_rows = document.getElementById('num_rows').value;
        const seats_per_row = document.getElementById('seats_per_row').value;
        
        const msgEl = document.getElementById('add-hall-msg');
        msgEl.innerHTML = '<div class="loading-inline"><div class="spinner-small"></div> Creating hall...</div>';
        
        fetch('admin/add_hall.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({name, num_rows, seats_per_row})
        })
        .then(res => res.json())
        .then(data => {
            msgEl.innerHTML = `<div class="alert ${data.success ? 'alert-success' : 'alert-error'}">
                <i class="fas ${data.success ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${data.message}
            </div>`;
            if(data.success) {
                toggleForm('add-hall-form');
                fetchHalls();
            }
        });
    };

    document.getElementById('movie-form').onsubmit = async function(e) {
    e.preventDefault();
    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;
    const genre = document.getElementById('genre').value;
    const duration = document.getElementById('duration').value;
    
    const msgEl = document.getElementById('add-movie-msg');
    msgEl.innerHTML = '<div class="loading-inline"><div class="spinner-small"></div> Adding movie...</div>';
    
    try {
        const poster_url = await getMovieImageUrl(title);
        
        console.log("Title", title, "Duration", duration, "Poster URL", poster_url, "Description", description, "Genre", genre);
        
        const response = await fetch('admin/add_movie.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({title, duration, poster_url, description, genre})
        });
        
        const data = await response.json();
        msgEl.innerHTML = `<div class="alert ${data.success ? 'alert-success' : 'alert-error'}">
            <i class="fas ${data.success ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${data.message}
        </div>`;
        
        if(data.success) {
            toggleForm('add-movie-form');
            fetchMovies();
        }
    } catch (error) {
        console.error('Error adding movie:', error);
        msgEl.innerHTML = `<div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> Error adding movie. Please try again.
        </div>`;
    }
};
    document.getElementById('showtime-form').onsubmit = function(e) {
        e.preventDefault();
        const movie_id = document.getElementById('movie_id').value;
        const hall_id = document.getElementById('hall_id').value;
        const start_time = document.getElementById('start_time').value;
        const price = document.getElementById('price').value;
        
        const msgEl = document.getElementById('add-showtime-msg');
        msgEl.innerHTML = '<div class="loading-inline"><div class="spinner-small"></div> Adding showtime...</div>';
        
        fetch('admin/add_showtime.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({movie_id, hall_id, start_time, price})
        })
        .then(res => res.json())
        .then(data => {
            msgEl.innerHTML = `<div class="alert ${data.success ? 'alert-success' : 'alert-error'}">
                <i class="fas ${data.success ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${data.message}
            </div>`;
            if(data.success) {
                this.reset();
                setTimeout(() => toggleForm('showtime-form'), 1500);
            }
        });
    };


        async function getMovieImageUrl(title) {
            try {
                const apiKey = '6c979a3f4711b9d5d0a34501a85eced3';
                const response = await fetch(`https://api.themoviedb.org/3/search/movie?api_key=${apiKey}&query=${encodeURIComponent(title)}`);
                const data = await response.json();
                
                if (data.results && data.results.length > 0) {
                    const movie = data.results[0];
                    return movie.poster_path
                        ? `https://image.tmdb.org/t/p/w500${movie.poster_path}`
                        : 'https://via.placeholder.com/500x750?text=No+poster';
                } else {
                    return 'https://via.placeholder.com/500x750?text=No+poster';
                }
            } catch (error) {
                console.error('Error fetching movie poster:', error);
                return 'https://via.placeholder.com/500x750?text=No+poster';
            }
        }
    flatpickr("#start_time", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        minDate: "today"
    });

    fetchUsers();
    fetchHalls();
    fetchMovies();
    </script>
</body>
</html>