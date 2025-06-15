<?php
session_start();
$showtime_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Ticket - Cinema</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
        <?php
        if (isset($_GET['message'])) {
            echo '<div class="alert alert-info"><i class="fas fa-info-circle"></i> ' . htmlspecialchars($_GET['message']) . '</div>';
        }
        ?>
        
        <h1><i class="fas fa-ticket-alt"></i> Buy Ticket</h1>
        <?php include 'includes/nav.php'; ?>

    

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
            <div style="display: flex; gap: 30px; margin-top: 20px;">
                <div style="flex: 0 0 350px; max-width: 350px;">
                    <div class="form-container">
                        <div class="form-card">
                            <h3><i class="fas fa-shopping-cart"></i> Ticket Details</h3>
                            <div id="showtime-info"></div>
                            <form id="ticket-form">
                                <input type="hidden" name="showtime_id" value="<?php echo $showtime_id; ?>">
                                
                                <div class="form-group">
                                    <label for="ticket_count"><i class="fas fa-users"></i> Number of Tickets</label>
                                    <input type="number" id="ticket_count" name="ticket_count" min="1" max="10" value="1" required>
                                </div>

                                <div class="seat-legend">
                                    <h4><i class="fas fa-info-circle"></i> Seat Legend</h4>
                                    <div class="legend-item">
                                        <span class="seat available"></span>
                                        <span>Available</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="seat selected"></span>
                                        <span>Selected</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="seat reserved"></span>
                                        <span>Reserved</span>
                                    </div>
                                </div>

                                <div class="selected-seats-info">
                                    <h4><i class="fas fa-chair"></i> Selected Seats</h4>
                                    <div id="selected-seats-list">
                                        <p class="no-selection">No seats selected</p>
                                    </div>
                                </div>

                                <div class="total-price">
                                    <h4><i class="fas fa-calculator"></i> Total Price</h4>
                                    <div id="total-amount">0€</div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn-primary" id="confirm-btn" disabled>
                                        <i class="fas fa-credit-card"></i> Confirm Purchase
                                    </button>
                                </div>
                                <div id="purchase-msg" class="form-message"></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div style="flex: 1;">
                    <div class="section-header">
                        <h2><i class="fas fa-chair"></i> Select Your Seats</h2>
                    </div>
                    
                    <div class="seat-selection-container">
                        <div class="cinema-screen">
                            <i class="fas fa-desktop"></i> SCREEN
                        </div>
                        <div id="seat-container" class="loading">
                            <div class="spinner"></div>
                            <p>Loading seats...</p>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <h2>
                <a href="index.php" style="text-decoration: none; color: white">Log in</a> to buy a ticket.
            </h2>
        <?php endif; ?>

    <script>
        const showtimeId = <?php echo json_encode($showtime_id); ?>;
        let showtimeData = null;
        let selectedSeats = [];

        function updateSelectedSeatsDisplay() {
            const listElement = document.getElementById('selected-seats-list');
            const totalElement = document.getElementById('total-amount');
            const confirmBtn = document.getElementById('confirm-btn');
            
            if (selectedSeats.length === 0) {
                listElement.innerHTML = '<p class="no-selection">No seats selected</p>';
                totalElement.textContent = '0€';
                confirmBtn.disabled = true;
            } else {
                listElement.innerHTML = selectedSeats.map(seat => 
                    `<div class="selected-seat-item">
                        <i class="fas fa-chair"></i> Row ${seat.row}, Seat ${seat.number}
                    </div>`
                ).join('');
                
                const total = selectedSeats.length * parseFloat(showtimeData.showtime.price);
                totalElement.textContent = total.toFixed(0) + '€';
                confirmBtn.disabled = false;
            }
        }

        function loadSeatsAndShowtime() {
            fetch('api/get_seats_and_showtime.php?id=' + showtimeId)
                .then(response => response.json())
                .then(data => {
                    showtimeData = data;
                    
                    document.getElementById('showtime-info').innerHTML = `
                        <div class="dashboard-stats">
                                <p><i class="fas fa-film"></i> Movie: ${data.showtime.title}</p>
                                <p><i class="fas fa-clock"></i> Start Time: ${data.showtime.start_time}</p>
                                <p><i class="fas fa-hotel"></i> Hall: ${data.showtime.hall_name}</p>
                                <p><i class="fas fa-euro-sign"></i> Price: ${data.showtime.price}€</p>
                        </div>
                    `;

                    const seatContainer = document.getElementById('seat-container');
                    seatContainer.innerHTML = '';
                    
                    for (const row in data.seats) {
                        const rowDiv = document.createElement('div');
                        rowDiv.className = 'seat-row';
                        rowDiv.innerHTML = `<span class="row-label">${row}</span>`;
                        
                        const seatsDiv = document.createElement('div');
                        seatsDiv.className = 'seats-in-row';
                        
                        for (const num in data.seats[row]) {
                            const seat = data.seats[row][num];
                            const seatId = seat.id;
                            const isReserved = data.reserved[seatId];
                            
                            const seatElement = document.createElement('div');
                            seatElement.className = `seat ${isReserved ? 'reserved' : 'available'}`;
                            seatElement.textContent = num;
                            seatElement.dataset.seatId = seatId;
                            seatElement.dataset.row = row;
                            seatElement.dataset.number = num;
                            
                            if (!isReserved) {
                                seatElement.addEventListener('click', function() {
                                    toggleSeat(this);
                                });
                            }
                            
                            seatsDiv.appendChild(seatElement);
                        }
                        
                        rowDiv.appendChild(seatsDiv);
                        seatContainer.appendChild(rowDiv);
                    }
                    
                    updateSelectedSeatsDisplay();
                })
                .catch(error => {
                    document.getElementById('showtime-info').innerHTML = `
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> Error loading showtime information
                        </div>
                    `;
                });
        }

        function toggleSeat(seatElement) {
            const ticketCount = parseInt(document.getElementById('ticket_count').value) || 1;
            const seatId = seatElement.dataset.seatId;
            const row = seatElement.dataset.row;
            const number = seatElement.dataset.number;
            
            if (seatElement.classList.contains('selected')) {
                seatElement.classList.remove('selected');
                seatElement.classList.add('available');
                selectedSeats = selectedSeats.filter(seat => seat.id !== seatId);
            } else {
                if (selectedSeats.length >= ticketCount) {
                    showMessage('You can only select ' + ticketCount + ' seat(s)', 'warning');
                    return;
                }
                
                seatElement.classList.remove('available');
                seatElement.classList.add('selected');
                selectedSeats.push({
                    id: seatId,
                    row: row,
                    number: number
                });
            }
            
            updateSelectedSeatsDisplay();
        }

        function showMessage(message, type = 'info') {
            const msgEl = document.getElementById('purchase-msg');
            msgEl.innerHTML = `<div class="alert alert-${type}">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i> ${message}
            </div>`;
        }

        document.getElementById('ticket_count').addEventListener('input', function() {
            const newCount = parseInt(this.value) || 1;
            
            if (selectedSeats.length > newCount) {
                const excess = selectedSeats.slice(newCount);
                excess.forEach(seat => {
                    const seatElement = document.querySelector(`[data-seat-id="${seat.id}"]`);
                    if (seatElement) {
                        seatElement.classList.remove('selected');
                        seatElement.classList.add('available');
                    }
                });
                selectedSeats = selectedSeats.slice(0, newCount);
                updateSelectedSeatsDisplay();
            }
        });

        document.getElementById('ticket-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const ticketCount = parseInt(document.getElementById('ticket_count').value) || 1;
            const msgEl = document.getElementById('purchase-msg');
            const total_amount = document.getElementById('total-amount').textContent;

            if (selectedSeats.length === 0) {
                msgEl.innerHTML = 'Please select at least one seat', 'error';
                return;
            }
            
            if (selectedSeats.length !== ticketCount) {
                msgEl.innerHTML = `Please select exactly ${ticketCount} seat(s)`, 'error';
                return;
            }
            
            msgEl.innerHTML = '<div class="loading-inline"><div class="spinner-small"></div> Processing purchase...</div>';
            
            window.location.href = `payment.php?showtime_id=${showtimeId}&ticket_count=${ticketCount}&seat_ids=${selectedSeats.map(seat => seat.id).join(',')}&total_amount=${encodeURIComponent(total_amount)}`;
        })

        document.getElementById('ticket-form').addEventListener("input", function(e) {
            document.getElementById('purchase-msg').innerHTML = '';
        });

        loadSeatsAndShowtime();
    </script>
</body>
</html>