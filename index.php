<!DOCTYPE html>
<html lang="en">
<?php
session_start();
?>
<head>
    <meta charset="UTF-8">
    <title>Pocetna</title>
    <link rel="stylesheet" href="styles/style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <h1>Welcome to KinoClub</h1>
    <?php include 'includes/nav.php' ?>
     <?php if (isset($_SESSION['id'])): ?>
            <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
       <?php if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin'): ?>
            <div class="feature-showcase">
                <div class="feature-item left-aligned">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #ffb74d, #ffa726);">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="feature-content" onclick="window.location.href='schedule.php'">
                        <h3>Buy Tickets</h3>
                        <p>Browse movies, select showtimes, and book your perfect cinema experience.</p>
                    </div>
                </div>
                <div class="feature-item right-aligned" onclick="window.location.href='my_tickets.php'">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                        <i class="fas fa-film"></i>
                    </div>
                    <div class="feature-content">
                        <h3>My Tickets</h3>
                        <p>View your upcoming movies, manage your bookings, and access your ticket history.</p>
                    </div>
                </div>
                <div class="feature-item left-aligned" onclick="window.location.href='profile.php'">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="feature-content">
                        <h3>My Profile</h3>
                        <p>Update your account settings and customize your KinoClub experience.</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="feature-showcase">
                <div class="feature-item left-aligned" onclick="window.location.href='schedule.php'">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #ffb74d, #ffa726);">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Review schedule</h3>
                        <p>Check the schedule.</p>
                    </div>
                </div>
                <div class="feature-item right-aligned" onclick="window.location.href='admin.php'">
                    <div class="feature-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Admin panel</h3>
                        <p>Manage movies, showtimes, and user accounts.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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
    <?php else: ?>
    <div class="form-box" id="login-box">
        <h2>Login</h2>
        <input type="text" id="login-username" placeholder="Username" required>
        <input type="password" id="login-password" placeholder="Password" required>
        <button id="login-btn">Login</button>
        <div class="msg" id="login-msg"></div>
        <div class="switch-link" onclick="showRegister()">Don't have an account? Register</div>
    </div>

    <div class="form-box" id="register-box" style="display:none;">
        <h2>Register</h2>
        <input type="text" id="register-username" placeholder="Username" required>
        <input type="email" id="register-email" placeholder="Email" required>
        <input type="password" id="register-password" placeholder="Password" required>
        <input type="password" id="register-confirm" placeholder="Confirm Password" required>
        <div id="password-strength" class="password-strength"></div>
        <button id="register-btn">Register</button>
        <div class="msg" id="register-msg"></div>
        <div class="switch-link" onclick="showLogin()">Already have an account? Login</div>
    </div>
    <script>
        function showRegister() {
            document.getElementById('login-box').style.display = 'none';
            document.getElementById('register-box').style.display = 'block';
            document.getElementById('login-msg').textContent = '';
        }
        function showLogin() {
            document.getElementById('register-box').style.display = 'none';
            document.getElementById('login-box').style.display = 'block';
            document.getElementById('register-msg').textContent = '';
        }

        window.onload = function() {
            const params = new URLSearchParams(window.location.search);
            if (params.get('show') === 'register') {
                showRegister();
            }
        };
    
        document.getElementById('login-btn').onclick = function() {
            const username = document.getElementById('login-username').value;
            const password = document.getElementById('login-password').value;
            fetch('auth/login.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({username, password})
            })
            .then(res => res.json())
            .then(data => {
                const msg = document.getElementById('login-msg');
                msg.textContent = data.message || '';
                msg.className = 'msg' + (data.success ? ' success' : '');
                if(data.success) {
                    window.location.href = 'index.php'
                }
            });
        };
        
        document.getElementById('register-btn').onclick = function() {
            const username = document.getElementById('register-username').value;
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            const confirm_password = document.getElementById('register-confirm').value;
            if (checkPasswordStrength(password) < 2) {
                document.getElementById('register-msg').textContent = 'Password is too weak.';
                return;
            }
            fetch('auth/register.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({username, email, password, confirm_password})
            })
            .then(res => res.json())
            .then(data => {
                const msg = document.getElementById('register-msg');
                msg.textContent = data.message || '';
                msg.className = 'msg' + (data.success ? ' success' : '');
                if(data.success) {
                    showLogin()
                }
            });
        };
        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            return strength;
        }
    </script>
    <?php endif; ?>
</body>
</html>