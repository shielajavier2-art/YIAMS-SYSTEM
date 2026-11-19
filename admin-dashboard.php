<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
$loggedName = $_SESSION['full_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YIAMS — Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
      
body {
    font-family: 'Inter', sans-serif;
    background:
        linear-gradient(
            115deg,
            rgba(128, 159, 199, 0.88),
            rgba(131, 132, 132, 0.88),
            rgba(10, 27, 49, 0.88)
        ),
        url("youth_logo.jpg") center center / cover no-repeat fixed;
    display: flex;
    min-height: 1000vh;
}




        /* === Sidebar === */
        .sidebar {
            width: 240px;
            background: #0f1c2e;
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        .sidebar-logo {
            padding: 24px 20px;
            font-size: 20px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-logo span { color: #b8860b; }
        .sidebar-nav { padding: 20px 0; flex: 1; }
        .sidebar-nav h5 {
            padding: 12px 20px 6px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            margin-top: 16px;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
            transition: 0.25s;
            gap: 10px;
            margin-bottom: 2px;
        }
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(184, 134, 11, 0.15);
            color: #b8860b;
            border-left: 3px solid #b8860b;
        }
        .badge-dot {
            margin-left: auto;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
        }

        /* === Main Content === */
        .main-content {
            flex: 1;
            margin-left: 240px;
            padding: 28px 36px;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }
        .page-title h2 {
            font-size: 26px;
            color: #0f1c2e;
            font-weight: 700;
        }
        .page-title p {
            font-size: 14px;
            color: #000104;
            margin-top: 6px;
        }

        /* === Profile Avatar === */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .avatar-wrapper {
            position: relative;
            cursor: pointer;
        }
        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #b8860b;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 18px;
            overflow: hidden;
        }
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .avatar-overlay {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: 0.25s;
            font-size: 14px;
        }
        .avatar-wrapper:hover .avatar-overlay { opacity: 1; }
        .avatar-input { display: none; }
        .user-info { text-align: right; }
        .user-info div { font-weight: 600; font-size: 14px; color: #0f1c2e; }
        .user-info small { color: #6b7280; font-size: 12px; }

        /* === Announcement Banner === */
        .announcement-banner {
            background: #eef2ff;
            border-left: 4px solid #3b82f6;
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: none;
            animation: fadeIn 0.3s ease;
        }
        .announcement-banner.show { display: block; }
        .announcement-banner h4 {
            color: #1e40af;
            font-size: 15px;
            margin-bottom: 4px;
        }
        .announcement-banner p {
            color: #050606;
            font-size: 14px;
            line-height: 1.5;
        }
        .announcement-banner small {
            color: #0f0f11;
            font-size: 12px;
            margin-top: 6px;
            display: inline-block;
        }
        .announcement-banner .close-btn {
            float: right;
            background: none;
            border: none;
            color: #02060c;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
        }

        /* === Tabs === */
        .tabs { display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 1px solid #e5e7eb; }
        .tab-btn {
            padding: 10px 20px;
            background: none;
            border: none;
            font-size: 14px;
            font-weight: 600;
            color: #03060d;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            margin-bottom: -1px;
        }
        .tab-btn.active {
            color: #b8860b;
            border-bottom-color: #b8860b;
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* === Cards & Panels === */
        .card {
            background: transparent;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f1c2e;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-label { font-size: 13px; color: #0e0f10; }
        .info-value { font-size: 14px; font-weight: 600; color: #1f2937; }

        /* === Form Elements === */
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin: 14px 0 6px;
        }
        input, select, textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-family: inherit;
            font-size: 14px;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #b8860b;
            box-shadow: 0 0 0 3px rgba(184,134,11,0.1);
        }
        textarea { min-height: 90px; resize: vertical; }

        /* === Buttons === */
        .btn {
            padding: 11px 20px;
            border: none;
            border-radius: 7px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.25s;
            margin-top: 14px;
        }
        .btn-primary { background: #b8860b; color: #fff; }
        .btn-primary:hover { background: #a0750a; transform: translateY(-1px); }
        .btn-secondary { background: #0f1c2e; color: #fff; }
        .btn-secondary:hover { background: #1a2d47; }
        .btn-success { background: #10b981; color: #fff; }
        .btn-success:hover { background: #059669; }
        .btn-sm { padding: 7px 14px; font-size: 13px; }
        .btn-outline { background: transparent; border: 1px solid #d1d5db; color: #374151; margin-left: 8px; }

        /* === Status Badges === */
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge.pending { background: #fef3c7; color: #b45309; }
        .badge.approved { background: #d1fae5; color: #065f46; }
        .badge.rejected { background: #fee2e2; color: #b91c1c; }
        .badge.processing { background: #dbeafe; color: #1e40af; }

        /* === List Items === */
        .list-item {
            padding: 16px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .list-item:last-child { border-bottom: none; }
        .item-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
        }
        .item-title { font-weight: 600; font-size: 14px; color: #0f1c2e; }
        .item-sub { font-size: 12px; color: #6b7280; margin-top: 4px; }
        .item-desc { font-size: 13px; color: #4b5563; margin-top: 6px; }

        /* === Hidden Forms === */
        .hidden-form { display: none; margin-top: 20px; padding-top: 20px; border-top: 1px dashed #e5e7eb; }
        .hidden-form.active { display: block; }

        /* === Stats Row === */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-box {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-box:nth-child(2) { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .stat-box:nth-child(3) { background: linear-gradient(135deg, #10b981, #059669); }
        .stat-box:nth-child(4) { background: linear-gradient(135deg, #6b7280, #4b5563); }
        .stat-val { font-size: 24px; font-weight: 700; }
        .stat-lab { font-size: 12px; opacity: 0.9; margin-top: 4px; }

        .empty-state { text-align: center; padding: 30px; color: #9ca3af; font-size: 14px; }

        @media (max-width: 900px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
            .grid-2 { grid-template-columns: 1fr; }
        }
        @media (max-width: 600px) {
            .sidebar { position: relative; width: 100%; height: auto; }
            .main-content { margin-left: 0; padding: 20px; }
            .stats-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            YIAMS <span>admin</span>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="active" data-tab="dashboard">📊 Dashboard</a>
            <a href="#" data-tab="profile">👤 Youth Records</a>
            <a href="#" data-tab="apply">📝  Program</a>
            <a href="#" data-tab="myapps">📄 Applications</a>
            <a href="#" data-tab="notifications">📋 Requirements <span class="badge-dot" id="notifBadge" style="display:none;">0</span></a>
            <a href="#" data-tab="feedback">📢 Notifications</a>
               <h5>Monitoring</h5>
            <a href="#" data-tab="Reports">📈 Reports</a>
            <a href="#" data-tab="Systemlogs">⚙️ System Logs</a>

            <h5>Settings</h5>
            <a href="#">🔧 Account Settings</a>
            <a href="logout.php" id="logoutLink">🚪 Log Out</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="page-title">
                <h2>Welcome, <span id="displayName"><?= htmlspecialchars($loggedName) ?></span>!</h2>
                <p>Barangay 22 Binanuahan East, Legazpi City — Youth Information & Assistance Management System</p>
            </div>
            <div class="user-profile">
                <div class="user-info">
                    <div id="userName"><?= htmlspecialchars($loggedName) ?></div>
                    <small>admin</small>
                </div>
                <label class="avatar-wrapper" title="Click to change profile picture">
                    <div class="user-avatar" id="avatarDisplay">
                        <span id="avatarLetter">Y</span>
                    </div>
                    <div class="avatar-overlay">📷</div>
                    <input type="file" class="avatar-input" id="avatarInput" accept="image/*">
                </label>
            </div>
        </div>

        <!-- Announcement Banner (Synced with Admin) -->
        <div class="announcement-banner" id="activeAnnBanner">
            <button class="close-btn" onclick="hideBanner()">&times;</button>
            <h4 id="bannerTitle"></h4>
            <p id="bannerText"></p>
            <small id="bannerMeta"></small>
        </div>

     

        <!-- ========== DASHBOARD OVERVIEW ========== -->
        <div class="tab-content active" id="tab-dashboard">
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-val" id="statTotalApps">0</div>
                    <div class="stat-lab">Total Applications</div>
                </div>
                <div class="stat-box">
                    <div class="stat-val" id="statPending">0</div>
                    <div class="stat-lab">Pending</div>
                </div>
                <div class="stat-box">
                    <div class="stat-val" id="statApproved">0</div>
                    <div class="stat-lab">Approved</div>
                </div>
                <div class="stat-box">
                    <div class="stat-val" id="statUnread">0</div>
                    <div class="stat-lab">Unread Notifications</div>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title">📢 Latest Announcements</h3>
                <div id="latestAnnList"></div>
            </div>

            <div class="card">
                <h3 class="card-title">📋 Recent Applications</h3>
                <div id="recentAppsList"></div>
            </div>
        </div>

        <!-- ========== YOUTH RECORDS TAB ========== -->
        <div class="tab-content" id="tab-profile">
            <div class="card">
                <h3 class="card-title">👤 Youth Records</h3>

                <div id="profileView">
                    <div class="grid-2">
                        <div>
                            <div class="info-row">
                                <span class="info-label">Full Name</span>
                                <span class="info-value" id="vName">—</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Youth ID</span>
                                <span class="info-value" id="vYid">—</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Age</span>
                                <span class="info-value" id="vAge">—</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Birthday</span>
                                <span class="info-value" id="vBday">—</span>
                            </div>
                        </div>
                        <div>
                            <div class="info-row">
                                <span class="info-label">Address</span>
                                <span class="info-value" id="vAddr">—</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Contact No.</span>
                                <span class="info-value" id="vContact">—</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email</span>
                                <span class="info-value" id="vEmail">—</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Registered On</span>
                                <span class="info-value" id="vRegDate">—</span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-secondary" id="editProfileBtn">✏️ Edit Record</button>
                </div>

                <!-- Edit Form -->
                <div id="profileForm" class="hidden-form">
                    <label>Full Name</label>
                    <input type="text" id="eName" placeholder="Enter your full name">
                    
                    <label>Age</label>
                    <input type="number" id="eAge" min="1" max="120">
                    
                    <label>Birthday</label>
                    <input type="date" id="eBday">
                    
                    <label>Complete Address</label>
                    <input type="text" id="eAddr" placeholder="Barangay, Municipality, Province">
                    
                    <label>Contact Number</label>
                    <input type="tel" id="eContact" placeholder="09XX-XXX-XXXX">
                    
                    <label>Email Address</label>
                    <input type="email" id="eEmail" placeholder="your@email.com">
                    
                    <button class="btn btn-success" id="saveProfileBtn">💾 Save Changes</button>
                    <button class="btn btn-outline" id="cancelEditBtn">Cancel</button>
                </div>
            </div>
        </div>

        <!-- ========== APPLY TAB ========== -->
        <div class="tab-content" id="tab-apply">
            <div class="card">
                <h3 class="card-title">📝 Apply for Assistance Program</h3>
                <p style="color:#6b7280; font-size:14px; margin-bottom:20px;">Select an active program and submit your application.</p>

                <label>Select Program *</label>
                <select id="programSelect">
                    <option value="">-- Choose a program --</option>
                    <option value="Educational Assistance">Educational Assistance</option>
                    <option value="Livelihood Grant">Livelihood Grant</option>
                    <option value="Scholarship Program">Scholarship Program</option>
                    <option value="Youth Development Training">Youth Development Training</option>
                </select>

                <label>Purpose / Brief Message</label>
                <textarea id="purposeInput" placeholder="Tell us briefly why you are applying..."></textarea>

                <label>Upload Requirements / Documents</label>
                <input type="file" id="reqUpload" multiple accept=".pdf,.jpg,.png,.jpeg">
                <p style="font-size:12px; color:#08090c; margin-top:4px;">Optional — submit files if required by the program</p>

                <button class="btn btn-primary" id="submitAppBtn">✅ Submit Application</button>
            </div>
        </div>

        <!-- ========== MY APPLICATIONS TAB ========== -->
        <div class="tab-content" id="tab-myapps">
            <div class="card">
                <h3 class="card-title">📄 My Applications</h3>
                <div id="myAppsList"></div>
            </div>
        </div>


 <!-- ========== REPORTS TAB ========== -->
        <div class="tab-content" id="tab-Reports">
            <div class="card">
                <h3 class="card-title">📈 Reports</h3>
                <div id="Reports"></div>
            </div>
        </div> 

 <!-- ========== SYSTEM LOGS TAB ========== -->
        <div class="tab-content" id="tab-Systemlogs">
            <div class="card">
                <h3 class="card-title">⚙️ System Logs</h3>
                <div id="SystemLogs"></div>
            </div>
        </div>


        <!-- ========== NOTIFICATIONS TAB ========== -->
        <div class="tab-content" id="tab-notifications">
            <div class="card">
                <h3 class="card-title">🔔 Notifications & Updates</h3>
                <button class="btn btn-sm btn-outline" id="markAllReadBtn">Mark All as Read</button>
                <div id="notifList" style="margin-top:16px;"></div>
            </div>
        </div>

        <!-- ========== FEEDBACK TAB ========== -->
        <div class="tab-content" id="tab-feedback">
            <div class="card">
                <h3 class="card-title">💬 Submit Feedback</h3>
                
                <label>Your Feedback / Comment</label>
                <textarea id="feedbackText" placeholder="Share your experience, suggestions, or concerns..."></textarea>
                
                <button class="btn btn-primary" id="submitFeedbackBtn">📤 Submit Feedback</button>

                <div style="margin-top:28px;">
                    <h4 style="font-size:14px; font-weight:600; color:#010308; margin-bottom:12px;">Your Past Feedback</h4>
                    <div id="feedbackList"></div>
                </div>
            </div>
        </div>

        <!-- ========== TRANSACTION LOGS TAB ========== -->
        <div class="tab-content" id="tab-transactions">
            <div class="card">
                <h3 class="card-title">📋 My Transaction History</h3>
                <p style="color:#020919; font-size:14px; margin-bottom:16px;">All your actions and system activity are recorded here.</p>
                <div id="transLogList"></div>
            </div>
        </div>
    </main>

    <script>
        // === INITIALIZE ===
        document.addEventListener('DOMContentLoaded', function () {
            // Generate/load youth ID
            if (!localStorage.getItem('youthId')) {
                localStorage.setItem('youthId', 'Y-' + Date.now().toString().slice(-8));
            }
            youthId = localStorage.getItem('youthId');

            // Avatar
            const savedAvatar = localStorage.getItem('youthAvatar');
            if (savedAvatar) {
                document.getElementById('avatarDisplay').innerHTML = `<img src="${savedAvatar}" alt="Profile">`;
            }

            // Profile data
            loadProfile();
            
            // Announcements
            showLatestAnnouncement();
            loadAnnouncementsList();
            
            // Applications & stats
            loadMyApplications();
            updateDashboardStats();
            
            // Notifications
            loadNotifications();
            
            // Feedback
            loadFeedback();
            
            // Transaction logs
            loadTransactions();

            // Welcome flag
            if (!sessionStorage.getItem('youthLoggedIn')) {
                // First visit — keep banner visible
            } else {
                sessionStorage.removeItem('youthLoggedIn');
            }

            // Logout
            document.getElementById('logoutLink').addEventListener('click', function (e) {
                e.preventDefault();
                sessionStorage.clear();
                window.location.href = 'logout.php';
            });
        });

        let youthId = '';

        // === TAB SWITCHING ===
        document.querySelectorAll('.tab-btn, [data-tab]').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                const tabId = btn.dataset.tab;
                
                // Update nav links
                document.querySelectorAll('.sidebar-nav a').forEach(a => a.classList.remove('active'));
                document.querySelector(`.sidebar-nav a[data-tab="${tabId}"]`)?.classList.add('active');
                
                // Update tab buttons
                document.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
                document.querySelectorAll(`[data-tab="${tabId}"]`).forEach(t => t.classList.add('active'));
                
                // Show content
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                document.getElementById(`tab-${tabId}`).classList.add('active');
            });
        });

        // === AVATAR UPLOAD ===
        document.getElementById('avatarInput').addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file || !file.type.startsWith('image/')) {
                alert('⚠️ Please select an image file'); return;
            }
            const reader = new FileReader();
            reader.onload = ev => {
                const data = ev.target.result;
                document.getElementById('avatarDisplay').innerHTML = `<img src="${data}" alt="Profile">`;
                localStorage.setItem('youthAvatar', data);
                logTransaction('Updated profile picture');
                alert('✅ Profile picture updated!');
            };
            reader.readAsDataURL(file);
        });

        // === PROFILE MANAGEMENT ===
        function loadProfile() {
            const name = localStorage.getItem('youthName') || 'Youth User';
            document.getElementById('vName').textContent = name;
            document.getElementById('eName').value = name;

            document.getElementById('vYid').textContent = youthId;

            const age = localStorage.getItem('youthAge') || 'Not set';
            document.getElementById('vAge').textContent = age;
            document.getElementById('eAge').value = localStorage.getItem('youthAge') || '';

            const bday = localStorage.getItem('youthBday');
            document.getElementById('vBday').textContent = bday ? formatDate(bday) : 'Not set';
            document.getElementById('eBday').value = bday || '';

            const addr = localStorage.getItem('youthAddr') || 'Not set';
            document.getElementById('vAddr').textContent = addr;
            document.getElementById('eAddr').value = addr;

            const contact = localStorage.getItem('youthContact') || 'Not set';
            document.getElementById('vContact').textContent = contact;
            document.getElementById('eContact').value = contact;

            const email = localStorage.getItem('youthEmail') || 'Not set';
            document.getElementById('vEmail').textContent = email;
            document.getElementById('eEmail').value = email;

            const regDate = localStorage.getItem('youthRegDate') || new Date().toLocaleDateString('en-PH');
            document.getElementById('vRegDate').textContent = regDate;
            if (!localStorage.getItem('youthRegDate')) {
                localStorage.setItem('youthRegDate', regDate);
            }
        }

        document.getElementById('editProfileBtn').addEventListener('click', () => {
            document.getElementById('profileView').style.display = 'none';
            document.getElementById('profileForm').classList.add('active');
        });
        document.getElementById('cancelEditBtn').addEventListener('click', () => {
            document.getElementById('profileForm').classList.remove('active');
            document.getElementById('profileView').style.display = 'block';
        });
        document.getElementById('saveProfileBtn').addEventListener('click', () => {
            const name = document.getElementById('eName').value.trim();
            if (!name) { alert('⚠️ Name is required'); return; }

            localStorage.setItem('youthName', name);
            localStorage.setItem('youthAge', document.getElementById('eAge').value);
            localStorage.setItem('youthBday', document.getElementById('eBday').value);
            localStorage.setItem('youthAddr', document.getElementById('eAddr').value.trim());
            localStorage.setItem('youthContact', document.getElementById('eContact').value.trim());
            localStorage.setItem('youthEmail', document.getElementById('eEmail').value.trim());

            loadProfile();
            document.getElementById('profileForm').classList.remove('active');
            document.getElementById('profileView').style.display = 'block';
            logTransaction('Updated profile information');
            alert('✅ Profile saved!');
        });

        function formatDate(d) {
            return new Date(d).toLocaleDateString('en-PH', { year:'numeric', month:'short', day:'numeric' });
        }

        // === APPLY FOR PROGRAM ===
        document.getElementById('submitAppBtn').addEventListener('click', () => {
            const program = document.getElementById('programSelect').value;
            const purpose = document.getElementById('purposeInput').value.trim();
            
            if (!program) { alert('⚠️ Please select a program'); return; }

            const allApps = JSON.parse(localStorage.getItem('youthApps') || '[]');
            const newApp = {
                id: 'APP-' + Date.now().toString().slice(-6),
                youthId: youthId,
                program: program,
                purpose: purpose || '—',
                date: new Date().toLocaleString('en-PH'),
                status: 'Pending',
                documents: document.getElementById('reqUpload').files.length + ' file(s) attached'
            };

            allApps.unshift(newApp);
            localStorage.setItem('youthApps', JSON.stringify(allApps));
            
            // Add notification
            addNotification(`New Application: ${program}`, 'Your application has been submitted and is Pending review.');
            
            // Log transaction
            logTransaction(`Submitted application: ${program}`);

            // Reset form
            document.getElementById('programSelect').value = '';
            document.getElementById('purposeInput').value = '';
            document.getElementById('reqUpload').value = '';

            loadMyApplications();
            updateDashboardStats();
            alert('✅ Application submitted successfully!');
        });

        // === LOAD MY APPLICATIONS ===
        function loadMyApplications() {
            const allApps = JSON.parse(localStorage.getItem('youthApps') || '[]');
            const myApps = allApps.filter(a => a.youthId === youthId);
            
            const container = document.getElementById('myAppsList');
            const recent = document.getElementById('recentAppsList');

            if (myApps.length === 0) {
                container.innerHTML = '<div class="empty-state">You haven\'t submitted any applications yet.</div>';
                recent.innerHTML = '<p style="color:#9ca3af; font-size:13px;">No applications yet.</p>';
                return;
            }

            const html = myApps.map(app => `
                <div class="list-item">
                    <div class="item-head">
                        <div>
                            <div class="item-title">${escapeHtml(app.program)}</div>
                            <div class="item-sub">${app.id} • ${app.date}</div>
                            ${app.purpose !== '—' ? `<div class="item-desc">${escapeHtml(app.purpose)}</div>` : ''}
                        </div>
                        <span class="badge ${app.status.toLowerCase()}">${app.status}</span>
                    </div>
                    ${app.reviewedOn ? `<div class="item-sub" style="margin-top:8px;">Reviewed by ${app.reviewedBy || 'Admin'} on ${app.reviewedOn}</div>` : ''}
                </div>
            `).join('');

            container.innerHTML = html;
            recent.innerHTML = myApps.slice(0, 3).map(app => `
                <div class="list-item" style="padding:10px 0;">
                    <div class="item-head">
                        <span class="item-title" style="font-size:13px;">${escapeHtml(app.program)}</span>
                        <span class="badge ${app.status.toLowerCase()}" style="font-size:10px;">${app.status}</span>
                    </div>
                </div>
            `).join('') || '<p style="color:#9ca3af; font-size:13px;">No applications yet.</p>';
        }

        // === DASHBOARD STATS ===
        function updateDashboardStats() {
            const allApps = JSON.parse(localStorage.getItem('youthApps') || '[]');
            const myApps = allApps.filter(a => a.youthId === youthId);
            
            document.getElementById('statTotalApps').textContent = myApps.length;
            document.getElementById('statPending').textContent = myApps.filter(a => a.status === 'Pending').length;
            document.getElementById('statApproved').textContent = myApps.filter(a => a.status === 'Approved').length;
            
            const notifs = JSON.parse(localStorage.getItem('youthNotifs') || '[]').filter(n => !n.read);
            document.getElementById('statUnread').textContent = notifs.length;
            
            const badge = document.getElementById('notifBadge');
            if (notifs.length > 0) {
                badge.style.display = 'inline';
                badge.textContent = notifs.length;
            } else {
                badge.style.display = 'none';
            }
        }

        // === ANNOUNCEMENTS ===
        function showLatestAnnouncement() {
            const anns = JSON.parse(localStorage.getItem('announcements') || '[]');
            const banner = document.getElementById('activeAnnBanner');
            
            if (anns.length === 0) {
                banner.classList.remove('show');
                return;
            }
            const latest = anns[0];
            document.getElementById('bannerTitle').textContent = '📢 ' + latest.title;
            document.getElementById('bannerText').textContent = latest.body;
            document.getElementById('bannerMeta').textContent = `${latest.author} • ${latest.postedAt}`;
            banner.classList.add('show');
        }
        function hideBanner() {
            document.getElementById('activeAnnBanner').classList.remove('show');
        }
        function loadAnnouncementsList() {
            const anns = JSON.parse(localStorage.getItem('announcements') || '[]');
            const container = document.getElementById('latestAnnList');
            
            if (anns.length === 0) {
                container.innerHTML = '<p style="color:#9ca3af; font-size:13px;">No announcements at this time.</p>';
                return;
            }
            container.innerHTML = anns.slice(0, 3).map(ann => `
                <div class="list-item">
                    <div class="item-title">📢 ${escapeHtml(ann.title)}</div>
                    <div class="item-desc">${escapeHtml(ann.body)}</div>
                    <div class="item-sub">${ann.author} • ${ann.postedAt}</div>
                </div>
            `).join('');
        }

        // === NOTIFICATIONS SYSTEM ===
        function addNotification(title, message) {
            const notifs = JSON.parse(localStorage.getItem('youthNotifs') || '[]');
            notifs.unshift({
                id: Date.now(),
                title,
                message,
                date: new Date().toLocaleString('en-PH'),
                read: false
            });
            localStorage.setItem('youthNotifs', JSON.stringify(notifs));
            loadNotifications();
            updateDashboardStats();
        }
        function loadNotifications() {
            const notifs = JSON.parse(localStorage.getItem('youthNotifs') || '[]');
            const container = document.getElementById('notifList');
            
            if (notifs.length === 0) {
                container.innerHTML = '<div class="empty-state">No notifications yet.</div>';
                return;
            }
            container.innerHTML = notifs.map(n => `
                <div class="list-item" style="opacity:${n.read ? 0.6 : 1};">
                    <div class="item-head">
                        <div class="item-title">${n.read ? '📌' : '🔔'} ${escapeHtml(n.title)}</div>
                        ${!n.read ? '<span style="color:#b8860b; font-size:11px; font-weight:600;">NEW</span>' : ''}
                    </div>
                    <div class="item-desc">${escapeHtml(n.message)}</div>
                    <div class="item-sub">${n.date}</div>
                </div>
            `).join('');
            updateDashboardStats();
        }
        document.getElementById('markAllReadBtn').addEventListener('click', () => {
            let notifs = JSON.parse(localStorage.getItem('youthNotifs') || '[]');
            notifs = notifs.map(n => ({ ...n, read: true }));
            localStorage.setItem('youthNotifs', JSON.stringify(notifs));
            loadNotifications();
            updateDashboardStats();
        });

        // === FEEDBACK SYSTEM ===
        document.getElementById('submitFeedbackBtn').addEventListener('click', () => {
            const text = document.getElementById('feedbackText').value.trim();
            if (!text) { alert('⚠️ Please write your feedback first'); return; }

            const feedbacks = JSON.parse(localStorage.getItem('youthFeedback') || '[]');
            feedbacks.unshift({
                id: Date.now(),
                text,
                date: new Date().toLocaleString('en-PH')
            });
            localStorage.setItem('youthFeedback', JSON.stringify(feedbacks));

            document.getElementById('feedbackText').value = '';
            loadFeedback();
            logTransaction('Submitted feedback');
            alert('✅ Feedback submitted. Thank you!');
        });
        function loadFeedback() {
            const feedbacks = JSON.parse(localStorage.getItem('youthFeedback') || '[]');
            const container = document.getElementById('feedbackList');
            
            if (feedbacks.length === 0) {
                container.innerHTML = '<p style="color:#9ca3af; font-size:13px;">No feedback submitted yet.</p>';
                return;
            }
            container.innerHTML = feedbacks.map(f => `
                <div class="list-item">
                    <div class="item-desc">"${escapeHtml(f.text)}"</div>
                    <div class="item-sub">${f.date}</div>
                </div>
            `).join('');
        }

        // === TRANSACTION LOGS ===
        function logTransaction(action) {
            const logs = JSON.parse(localStorage.getItem('youthTransactions') || '[]');
            logs.unshift({
                id: Date.now(),
                action,
                dateTime: new Date().toLocaleString('en-PH')
            });
            localStorage.setItem('youthTransactions', JSON.stringify(logs.slice(0, 50)));
            loadTransactions();
        }
        function loadTransactions() {
            const logs = JSON.parse(localStorage.getItem('youthTransactions') || '[]');
            const container = document.getElementById('transLogList');
            
            if (logs.length === 0) {
                container.innerHTML = '<div class="empty-state">No transaction history yet.</div>';
                return;
            }
            container.innerHTML = logs.map(l => `
                <div class="list-item">
                    <div class="item-title">${l.action}</div>
                    <div class="item-sub">${l.dateTime}</div>
                </div>
            `).join('');
        }

        // === HELPER ===
        function escapeHtml(t) {
            const d = document.createElement('div');
            d.textContent = t;
            return d.innerHTML;
        }

        // Auto-check for status changes — add notification when admin updates
        setInterval(() => {
            const allApps = JSON.parse(localStorage.getItem('youthApps') || '[]');
            const stored = localStorage.getItem('lastCheckedApps') || '[]';
            
            allApps.forEach(app => {
                if (app.youthId === youthId && app.reviewedOn) {
                    // Simple detection — could be enhanced with timestamps
                }
            });
            localStorage.setItem('lastCheckedApps', JSON.stringify(allApps));
        }, 30000); // Every 30 seconds
    </script>
</body>
</html>