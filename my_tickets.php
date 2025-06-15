<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
        <h1><i class="fas fa-ticket-alt"></i> My Tickets</h1>
        <?php include 'includes/nav.php'; ?>
        
        <div class="tickets-header">
            <h2><i class="fas fa-ticket-alt"></i> Tickets</h2>
            <div class="ticket-filter" style="display: flex; gap: 10px; justify-content: center;">
                <button class="filter-btn active" data-filter="all">
                    <i class="fas fa-list"></i>
                    All
                    <span class="filter-count" id="all-count">0</span>
                </button>
                <button class="filter-btn" data-filter="upcoming">
                    <i class="fas fa-calendar-check"></i>
                    Upcoming
                    <span class="filter-count" id="upcoming-count">0</span>
                </button>
                <button class="filter-btn" data-filter="past">
                    <i class="fas fa-history"></i>
                    Past
                    <span class="filter-count" id="past-count">0</span>
                </button>
            </div>
        </div>
                
        <div id="tickets">
            <div class="loading">
                <div class="spinner"></div>
                <p>Loading your tickets...</p>
            </div>
        </div>

    <script>
        let allTicketGroups = [];
        let currentFilter = 'all';
        
        function groupTicketsByShowtime(tickets) {
            const groups = {};
            
            tickets.forEach(ticket => {
                const key = `${ticket.movie_title}_${ticket.start_time}_${ticket.hall_name}`;
                if (!groups[key]) {
                    groups[key] = {
                        movie_title: ticket.movie_title,
                        start_time: ticket.start_time,
                        hall_name: ticket.hall_name,
                        poster_url: ticket.poster_url,
                        price: ticket.price,
                        tickets: []
                    };
                }
                groups[key].tickets.push(ticket);
            });
            
            return Object.values(groups);
        }
        
        function formatDateTime(dateTime) {
            const date = new Date(dateTime);
            const options = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return date.toLocaleDateString('en-US', options);
        }
        
        function isUpcoming(dateTime) {
            return new Date(dateTime) > new Date();
        }
        
        function updateFilterCounts() {
            const upcomingGroups = allTicketGroups.filter(g => isUpcoming(g.start_time));
            const pastGroups = allTicketGroups.filter(g => !isUpcoming(g.start_time));
            
            document.getElementById('all-count').textContent = allTicketGroups.length;
            document.getElementById('upcoming-count').textContent = upcomingGroups.length;
            document.getElementById('past-count').textContent = pastGroups.length;
        }
        
        function filterTickets(filter) {
            currentFilter = filter;
            let filteredGroups;
            
            switch(filter) {
                case 'upcoming':
                    filteredGroups = allTicketGroups.filter(g => isUpcoming(g.start_time));
                    break;
                case 'past':
                    filteredGroups = allTicketGroups.filter(g => !isUpcoming(g.start_time));
                    break;
                default:
                    filteredGroups = allTicketGroups;
            }
            
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-filter="${filter}"]`).classList.add('active');
            
            renderFilteredTickets(filteredGroups);
        }
        
        function renderFilteredTickets(groups) {
            const ticketsDiv = document.getElementById('tickets');
            
            if (groups && groups.length > 0) {
                groups.sort((a, b) => new Date(a.start_time) - new Date(b.start_time));
                
                let html = '';
                groups.forEach(group => {
                    const isUpcomingShow = isUpcoming(group.start_time);
                    const statusClass = isUpcomingShow ? 'upcoming' : 'past';
                    
                    html += `
                        <div class="ticket-group ${statusClass}">
                            <div class="ticket-group-header">
                                <div class="ticket-poster">
                                    <img src="${group.poster_url}"
                                         alt="Movie poster">
                                </div>
                                <div class="ticket-group-info">
                                    <h3>${group.movie_title}</h3>
                                    <div class="ticket-group-meta">
                                        <div class="ticket-meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span>${group.start_time}</span>
                                        </div>
                                        <div class="ticket-meta-item">
                                            <i class="fas fa-hotel"></i>
                                            <span>${group.hall_name}</span>
                                        </div>
                                        ${isUpcomingShow ? 
                                            '<div class="ticket-meta-item"><i class="fas fa-calendar-check" style="color: green;"></i><span style="color: green;">Upcoming</span></div>' : 
                                            '<div class="ticket-meta-item"><i class="fas fa-history" style="color: #888;"></i><span style="color: #888;">Past</span></div>'
                                        }
                                    </div>
                                </div>
                                <div class="ticket-count">${group.tickets.length}</div>
                            </div>
                            <div class="ticket-seats">
                    `;
                    
                    group.tickets.forEach(ticket => {
                        html += `
                            <div class="seat-card">
                                <div class="seat-number">
                                    <i class="fas fa-chair"></i> ${ticket.seat_number}
                                </div>
                                <div class="seat-row">Row ${ticket.seat_row}</div>
                            </div>
                        `;
                    });
                    
                    html += `
                            </div>
                        </div>
                    `;
                });
                
                ticketsDiv.innerHTML = html;
            } else {
                let message = '';
                switch(currentFilter) {
                    case 'upcoming':
                        message = 'No upcoming tickets found. <a href="schedule.php" style="text-decoration: none; color: white;">Browse available shows</a> to book new tickets!';
                        break;
                    case 'past':
                        message = 'No past tickets found.';
                        break;
                    default:
                        message = 'You haven\'t purchased any tickets yet. <a href="schedule.php" style="text-decoration: none; color: white;">Browse available shows</a> to get started!';
                }
                
                ticketsDiv.innerHTML = `
                    <div class="no-tickets">
                        <i class="fas fa-ticket-alt"></i>
                        <h3>No tickets found</h3>
                        <p>${message}</p>
                    </div>
                `;
            }
        }
        
        function renderTickets(data) {
            if (data && data.length > 0) {
                allTicketGroups = groupTicketsByShowtime(data);
                updateFilterCounts();
                filterTickets(currentFilter);
            } else {
                allTicketGroups = [];
                updateFilterCounts();
                renderFilteredTickets([]);
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    filterTickets(filter);
                });
            });
        });
        
        
        fetch("api/get_tickets.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id: <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 0; ?> })
        })
        .then(res => res.json())
        .then(data => {
            renderTickets(data);
        })
        .catch(error => {
            console.error('Error fetching tickets:', error);
            document.getElementById('tickets').innerHTML = `
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> 
                    Error loading tickets. Please try again later.
                </div>
            `;
        });
    </script>
</body>
</html>