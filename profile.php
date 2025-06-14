<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <h1>User Profile</h1>
    <?php include 'includes/nav.php'; ?>
    <div id="profile-info"></div>
    <script>
        fetch("api/get_profile_data.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id: <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 0; ?> })
        })
        .then(res => res.json())
        .then(data => {
            if (data) {
                const profileInfo = document.getElementById('profile-info');
                profileInfo.innerHTML = `
                    <h2>${data.username}'s Profile</h2>
                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>Role:</strong> ${data.role}</p>
                    <p><strong>Registered on:</strong> ${new Date(data.registered_at).toLocaleDateString()}</p>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching profile data:', error);
        });
    </script>
</body>
</html>