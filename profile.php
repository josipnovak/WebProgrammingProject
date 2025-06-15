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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <h1><i class="fas fa-user-circle"></i> My Profile</h1>
    <?php include 'includes/nav.php'; ?>

    <h2><i class="fas fa-user"></i></h2>
    
    <div class="profile-header">
        <div class="profile-tabs">
            <button class="tab-btn active" data-tab="personal">
                <i class="fas fa-user"></i>
                Personal Info
            </button>
            <button class="tab-btn" data-tab="security">
                <i class="fas fa-shield-alt"></i>
                Security
            </button>
            <button class="tab-btn" data-tab="activity">
                <i class="fas fa-chart-line"></i>
                Activity
            </button>
        </div>
    </div>

    <div id="personal-tab" class="tab-content active">
        <div class="profile-section">
            <div id="profile-info" class="profile-card">
                <div class="loading">
                    <div class="spinner"></div>
                    <p>Loading profile information...</p>
                </div>
            </div>
        </div>
    </div>

    <div id="security-tab" class="tab-content">
        <div class="profile-section">
            <div class="section-header">
                <h2><i class="fas fa-shield-alt"></i> Security Settings</h2>
            </div>
            <div class="profile-card">
                <form id="change-password-form" class="security-form">
                    <div class="form-group">
                        <label for="current-password">
                            <i class="fas fa-lock"></i> Current Password
                        </label>
                        <input type="password" id="current-password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new-password">
                            <i class="fas fa-key"></i> New Password
                        </label>
                        <input type="password" id="new-password" name="new_password" required>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill"></div>
                            </div>
                            <span class="strength-text">Password strength</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">
                            <i class="fas fa-check-circle"></i> Confirm New Password
                        </label>
                        <input type="password" id="confirm-password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="activity-tab" class="tab-content">
        <div class="profile-section">
            <div class="section-header">
                <h2><i class="fas fa-chart-line"></i> Activity Overview</h2>
            </div>
            <div id="activity-stats" class="activity-grid">
                <div class="loading">
                    <div class="spinner"></div>
                    <p>Loading activity data...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let profileData = null;

        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const tabName = this.getAttribute('data-tab');
                    
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    this.classList.add('active');
                    document.getElementById(tabName + '-tab').classList.add('active');
                });
            });

            const newPasswordInput = document.getElementById('new-password');
            if (newPasswordInput) {
                newPasswordInput.addEventListener('input', checkPasswordStrength);
            }

            const passwordForm = document.getElementById('change-password-form');
            if (passwordForm) {
                passwordForm.addEventListener('submit', handlePasswordChange);
            }
        });

        function loadProfileData() {
            fetch("api/get_profile_data.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ id: <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 0; ?> })
            })
            .then(res => res.json())
            .then(data => {
                profileData = data;
                renderProfileInfo(data);
            })
            .catch(error => {
                console.error('Error fetching profile data:', error);
                document.getElementById('profile-info').innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> 
                        Error loading profile data. Please try again later.
                    </div>
                `;
            });
        }


        function renderProfileInfo(data) {
            if (data) {
                const profileInfo = document.getElementById('profile-info');
                profileInfo.innerHTML = `
                    <div class="profile-details">
                        <div class="profile-info-grid">
                            <div class="info-item">
                                <label><i class="fas fa-user"></i> Username</label>
                                <span>${data.username}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-envelope"></i> Email</label>
                                <span>${data.email}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-user-tag"></i> Role</label>
                                <span class="role-badge role-${data.role.toLowerCase()}">${data.role}</span>
                            </div>
                            <div class="info-item">
                                <label><i class="fas fa-calendar-alt"></i> Member Since</label>
                                <span>${new Date(data.registered_at).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric'
                                })}</span>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        function renderActivityStats(data) {
            if (data) {
                const activityStats = document.getElementById('activity-stats');
                activityStats.innerHTML = `
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">${data.total_tickets || 0}</div>
                            <div class="stat-label">Tickets Purchased</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">$${(data.total_spent || 0).toFixed(2)}</div>
                            <div class="stat-label">Total Spent</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-film"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">${data.movies_watched || 0}</div>
                            <div class="stat-label">Movies Watched</div>
                        </div>
                    </div>
                `;
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('new-password').value;
            const strengthBar = document.querySelector('.strength-fill');
            const strengthText = document.querySelector('.strength-text');
            
            let strength = 0;
            let strengthLabel = '';
            
            if (password.length >= 8) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
            
            switch(strength) {
                case 0:
                case 1:
                    strengthLabel = 'Very Weak';
                    strengthBar.style.width = '20%';
                    strengthBar.style.background = '#ff4444';
                    break;
                case 2:
                    strengthLabel = 'Weak';
                    strengthBar.style.width = '40%';
                    strengthBar.style.background = '#ff8800';
                    break;
                case 3:
                    strengthLabel = 'Fair';
                    strengthBar.style.width = '60%';
                    strengthBar.style.background = '#ffaa00';
                    break;
                case 4:
                    strengthLabel = 'Good';
                    strengthBar.style.width = '80%';
                    strengthBar.style.background = '#88cc00';
                    break;
                case 5:
                    strengthLabel = 'Strong';
                    strengthBar.style.width = '100%';
                    strengthBar.style.background = '#00cc44';
                    break;
            }
            
            strengthText.textContent = strengthLabel;
        }

        function handlePasswordChange(e) {
            e.preventDefault();
            
            const currentPassword = document.getElementById('current-password').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            if (newPassword !== confirmPassword) {
                alert('New passwords do not match!');
                return;
            }
            
            fetch("api/change_password.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 0; ?>,
                    current_password: currentPassword,
                    new_password: newPassword
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Password updated successfully!');
                    document.getElementById('change-password-form').reset();
                } else {
                    alert(data.message || 'Error updating password');
                }
            })
            .catch(error => {
                console.error('Error changing password:', error);
                alert('Error updating password. Please try again.');
            });
        }

        loadProfileData();
    </script>
</body>
</html>