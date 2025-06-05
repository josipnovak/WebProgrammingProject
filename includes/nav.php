<?php
?>
<nav>
    <ul style="list-style:none; display:flex; gap:20px; padding:0;">
        <li><a href="index.php">Home</a></li>
        <li><a href="schedule.php">Schedule</a></li>
        <?php if( isset($_SESSION['role']) && $_SESSION['role'] == 'admin') :?>
            <li><a href="admin.php">Admin Panel</a></li>
            <li>
                <form method="post" action="auth/logout.php" style="display:inline;">
                    <button name="logout" type="submit" style="background:none; border:none; color:blue; cursor:pointer;">Odjavi se</button>
                </form>
            </li>
        <?php elseif( isset($_SESSION['role']) && $_SESSION['role'] == 'user') :?>
            <li><a href="my_tickets.php">My tickets</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li>
                <form method="post" action="auth/logout.php" style="display:inline;">
                    <button name="logout" type="submit" style="background:none; border:none; color:blue; cursor:pointer;">Odjavi se</button>
                </form>
            </li>
        <?php else: ?>
            <li><a href="index.php">Prijava/Registracija</a></li>
        <?php endif; ?>
    </ul>
</nav>