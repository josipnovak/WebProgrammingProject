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

    <h2 id="profile-subtitle"></h2>
    
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
                <div class="section-header">
                    <h2><i class="fas fa-user"></i> User Details</h2>
                </div>
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
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">
                            <i class="fas fa-check-circle"></i> Confirm New Password
                        </label>
                        <input type="password" id="confirm-password" name="confirm_password" required>
                    </div>
                    <div class="msg" id="change-pass-msg"></div>
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
            const profileSubtitle = document.getElementById('profile-subtitle');
            fetch("api/get_profile_data.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ id: <?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 0; ?> })
            })
            .then(res => res.json())
            .then(data => {
                const i = document.createElement('i');
                i.className = 'fas fa-user';
                profileSubtitle.appendChild(i);
                profileSubtitle.innerHTML += " " + data.username || 'User';
                profileSubtitle.innerHTML += '\'\s Profile';
                profileData = data;
                renderProfileInfo(profileData);
                renderActivityStats(profileData);
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
                    <div style="display: flex; gap: 20px; flex-diraction: row; flex-wrap: wrap;">
                        <div class="stat-card">
                            <div class="stat-icon">
                               <i class="fas fa-user"></i> 
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">${data.username || ''}</div>
                                <div class="stat-label">Username</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-envelope"></i> 
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">${(data.email || '')}</div>
                                <div class="stat-label">Email</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-tag"></i> 
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">${new Date(data.registered_at).toLocaleDateString('en-GB', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}</div>
                                <div class="stat-label">Joined date</div>
                            </div>
                        </div>
                    </div>
                    
                `;
            }
        }

        function renderActivityStats(data) {
            console.log('Rendering activity stats:', data);
            if (data) {
                const activityStats = document.getElementById('activity-stats');
                activityStats.innerHTML = `
                    <div style="display: flex; gap: 20px; flex-diraction: row; flex-wrap: wrap;">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">${data.ticket_count || 0}</div>
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
                    </div>
                `;
            }
        }
        
        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            return strength;
        }

        function handlePasswordChange(e) {
            e.preventDefault();
            
            const currentPassword = document.getElementById('current-password').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const msg = document.getElementById('change-pass-msg');
            
            if (checkPasswordStrength(newPassword) < 2) {
                msg.innerHTML = 'New password is too weak.';
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
                    new_password: newPassword,
                    confirm_password: confirmPassword
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('change-password-form').reset();
                    msg.innerHTML = 'Password updated successfully!';
                } else {
                    msg.innerHTML = data.message || 'Error updating password';
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