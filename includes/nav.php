<?php
?>
<nav>
    <ul style="list-style:none; display:flex; gap:20px; padding:0;">
        <li><a href="index.php">Home</a></li>
        <li><a href="schedule.php">Schedule</a></li>
        <?php if( isset($_SESSION['role']) && $_SESSION['role'] == 'admin') :?>
            <li><a href="admin.php">Admin Panel</a></li>
            <li>
                <div>
                    <a id="logout-btn">Logout</a>
                </div>
                <script>
                    document.getElementById('logout-btn').onclick = function(e) {
                        e.preventDefault();
                        fetch('auth/logout.php', { method: 'POST' })
                            .then(res => res.json())
                            .then(data => {
                                window.location.href = 'index.php';
                            });
                    };
                </script>
            </li>
        <?php elseif( isset($_SESSION['role']) && $_SESSION['role'] == 'user') :?>
            <li><a href="my_tickets.php">My tickets</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li>
                <div>
                    <a id="logout-btn">Logout</a>
                </div>
                <script>
                    document.getElementById('logout-btn').onclick = function(e) {
                        e.preventDefault();
                        fetch('auth/logout.php', { method: 'POST' })
                            .then(res => res.json())
                            .then(data => {
                                window.location.href = 'index.php';
                            });
                    };
                </script>
            </li>
        <?php else: ?>
            <li><a href="index.php">Login</a></li>
            <li><a href="index.php?show=register">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>