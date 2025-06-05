<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
</head>
<body>
    <h1>Tickets</h1>
    <?php include 'includes/nav.php'; ?>
    <div id="tickets"></div>
    <script>
        fetch("api/get_tickets.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id: <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 0; ?> })
        })
        .then(res => res.json())
        .then(data => {
            if (data) {
                const profileInfo = document.getElementById('tickets');
                if (data.length > 0) {
                    profileInfo.innerHTML = `<h2>Your Tickets</h2>`;
                    data.forEach(ticket => {
                        profileInfo.innerHTML += `
                            <div class="ticket">
                                <p><strong>Movie Title:</strong> ${ticket.movie_title}</p>
                                <p><strong>Seat Row:</strong> ${ticket.seat_row}</p>
                                <p><strong>Seat Number:</strong> ${ticket.seat_number}</p>
                            </div>
                            <hr>
                        `;
                    });
                } else {
                    profileInfo.innerHTML = `<h2>No tickets found for ${data.username}.</h2>`;
                }
            }
        })
        .catch(error => {
            console.error('Error fetching profile data:', error);
        });
    </script>
</body>
</html>