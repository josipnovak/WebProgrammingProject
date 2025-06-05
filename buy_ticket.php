<?php
session_start();
$showtime_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy Ticket</title>
    <style>
        .seat { width: 30px; height: 30px; margin: 2px; display: inline-block; text-align: center; line-height: 30px; border: 1px solid #333; border-radius: 4px; cursor:pointer; }
        .reserved { background: #ccc; cursor:not-allowed; }
        .selected { background: #6c6; }
        .seat-row { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h1>Buy Ticket</h1>
    <div id="showtime-info"></div>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
        <form id="ticket-form">
            <input type="hidden" name="showtime_id" value="<?php echo $showtime_id; ?>">
            <div>
                <h3>Number of tickets:</h3>
                <input type="number" id="ticket_count" name="ticket_count" min="1" max="10" value="1" required>
            </div>
            <div>
                <h3>Select your seats:</h3>
                <div id="seat-container"></div>
            </div>
            <br>
            <button type="submit">Confirm Purchase</button>
        </form>
        <script>
        const showtimeId = <?php echo json_encode($showtime_id); ?>;
        fetch('api/get_seats_and_showtime.php?id=' + showtimeId)
            .then(response => response.json())
            .then(data => {
                document.getElementById('showtime-info').innerHTML = `
                    <p><strong>Movie:</strong> ${data.showtime.title}</p>
                    <p><strong>Hall:</strong> ${data.showtime.hall_name}</p>
                    <p><strong>Start Time:</strong> ${data.showtime.start_time}</p>
                    <p><strong>Price:</strong> ${data.showtime.price}€</p>
                `;

                const seatDiv = document.getElementById('seat-container');
                seatDiv.innerHTML = '';
                for (const row in data.seats) {
                    const rowDiv = document.createElement('div');
                    rowDiv.className = 'seat-row';
                    rowDiv.innerHTML = `<span>${row}</span> `;
                    for (const num in data.seats[row]) {
                        const seat = data.seats[row][num];
                        const seatId = seat.id;
                        const reserved = data.reserved[seatId] ? 'reserved' : '';
                        rowDiv.innerHTML += `
                            <label style="display:inline-block">
                                <input type="checkbox" class="seat-checkbox" name="seat_id[]" value="${seatId}" ${reserved ? "disabled" : ""} style="display:none;">
                                <span class="seat${reserved ? " reserved" : ""}">${num}</span>
                            </label>
                        `;
                    }
                    seatDiv.appendChild(rowDiv);
                }

                const ticketCountInput = document.getElementById('ticket_count');
                function enforceLimit() {
                    const checkboxes = document.querySelectorAll('.seat-checkbox');
                    const max = parseInt(ticketCountInput.value) || 1;
                    let checked = 0;
                    checkboxes.forEach(cb => {
                        if (cb.checked) checked++;
                    });
                    checkboxes.forEach(cb => {
                        if (!cb.checked) cb.disabled = (checked >= max) || cb.nextElementSibling.classList.contains('reserved');
                        else cb.disabled = false;
                    });
                }
                document.querySelectorAll('.seat-checkbox').forEach(function(checkbox) {
                    checkbox.addEventListener('change', function() {
                        if (checkbox.checked) {
                            checkbox.nextElementSibling.classList.add('selected');
                        } else {
                            checkbox.nextElementSibling.classList.remove('selected');
                        }
                        enforceLimit();
                    });
                });
                ticketCountInput.addEventListener('input', enforceLimit);
                enforceLimit();
            });

        document.getElementById('ticket-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const ticketCount = document.getElementById('ticket_count').value;
            const seatCheckboxes = document.querySelectorAll('.seat-checkbox:checked');
            const seatIds = Array.from(seatCheckboxes).map(cb => cb.value);

            fetch('api/process_ticket.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    showtime_id: showtimeId,
                    ticket_count: ticketCount,
                    seat_ids: seatIds
                })
            })
            .then(response => response.json())
            .then(result => {
                if(result.success) {
                    window.location.href = 'my_tickets.php';
                }
            });
        });
        </script>
    <?php else: ?>
        <p><a href="index.php">Please log in to buy a ticket.</a></p>
    <?php endif; ?>
</body>
</html>