<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
</head>
<body>
    <h1>Admin Panel</h1>
    <?php include 'includes/nav.php'; ?>
    <h2>Users</h2>
    <?php include 'admin/get_users.php'; ?>
    <h2>Halls</h2>
    <?php include 'admin/get_halls.php'; ?>
    <h2>Movies</h2>
    <?php include 'admin/get_movies.php'; ?>
    <h2>Showtimes</h2>
    <?php include 'admin/get_showtimes.php'; ?>
</body>
</html>