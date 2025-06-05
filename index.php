<!DOCTYPE html>
<html lang="en">
<?php
session_start();
?>
<head>
    <meta charset="UTF-8">
    <title>Pocetna</title>
    <style>
    </style>
</head>
<body>
    <?php include 'includes/nav.php' ?>
     <?php if (isset($_SESSION['id'])): ?>
        <div class="form-box">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>You are logged in as <strong><?php echo htmlspecialchars($_SESSION['role']); ?></strong>.</p>
            <button id="logout-btn">Logout</button>
        </div>
        <script>
            document.getElementById('logout-btn').onclick = function(e) {
                e.preventDefault();
                fetch('auth/logout.php', { method: 'POST' })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) window.location.href = 'index.php';
                    });
            };
        </script>
    <?php else: ?>
    <div class="form-box" id="login-box">
        <h2>Login</h2>
        <div class="msg" id="login-msg"></div>
        <input type="text" id="login-username" placeholder="Username" required>
        <input type="password" id="login-password" placeholder="Password" required>
        <button id="login-btn">Login</button>
        <div class="switch-link" onclick="showRegister()">Don't have an account? Register</div>
    </div>

    <div class="form-box" id="register-box" style="display:none;">
        <h2>Register</h2>
        <div class="msg" id="register-msg"></div>
        <input type="text" id="register-username" placeholder="Username" required>
        <input type="email" id="register-email" placeholder="Email" required>
        <input type="password" id="register-password" placeholder="Password" required>
        <input type="password" id="register-confirm" placeholder="Confirm Password" required>
        <button id="register-btn">Register</button>
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
    </script>
    <?php endif; ?>
</body>
</html>