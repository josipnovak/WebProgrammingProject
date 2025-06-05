<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
</head>
<body>
    <h1>Schedule</h1>
    <?php include 'includes/nav.php'; ?>
    <div id="showtimes"></div>
    <script>
        fetch("api/get_showtimes.php")
            .then(res => res.json())
            .then(data => {
                const showtimesDiv = document.getElementById("showtimes");
                if (data.length > 0) {
                    let html = "<table border='1'><tr><th>ID</th><th>Movie ID</th><th>Hall ID</th><th>Start Time</th><th>Price</th><th>Buy ticket</th></tr>";
                    data.forEach(show => {
                        html += `<tr>
                                    <td>${show.id}</td>
                                    <td>${show.movie_id}</td>
                                    <td>${show.hall_id}</td>
                                    <td>${show.start_time}</td>
                                    <td>${show.price}</td>
                                    <td><a href='buy_ticket.php?id=${show.id}'>Buy Ticket</a></td>
                                 </tr>`;
                    });
                    html += "</table>";
                    showtimesDiv.innerHTML = html;
                } else {
                    showtimesDiv.innerHTML = "<p>No showtimes found.</p>";
                }
            })
    </script>
</body>
</html>