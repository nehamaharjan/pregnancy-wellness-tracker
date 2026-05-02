<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include('../includes/db.php'); // DB connection $conn

$user_id = $_SESSION['user_id'];
$username = $email = $last_period = "";

// Fetch user details from DB
$stmt = $conn->prepare("SELECT username, email, last_period FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $last_period);
$stmt->fetch();
$stmt->close();
?>

<header class="main-header" style="
    position: sticky;
    top: 0;
    z-index: 1000;
    
">
    
    <!-- Logo -->
    <div class="logo-section" style="display:flex; align-items:center; gap:10px;">
        <a href="home.php" style="display:flex; align-items:center; gap:8px; text-decoration:none; color:black;">
            <img src="../assets/images/logo.png" alt="PregPal Logo" class="logo" style="height:40px;">
            <h1 style="margin:0; font-size:1.5em;">PregPal</h1>
        </a>
    </div>
       <div class="header-actions" style="display:flex; align-items:center; gap:20px;">
         <a href="../user/discussion_board.php"  style="text-decoration:none; color:#333; font-weight:500;">Clear your doubt</a>|
        <a href="home.php#insights"  style="text-decoration:none; color:#333; font-weight:500;">Insight</a>|
        <a href="../user/symptom_chart.php" style="text-decoration:none; color:#333; font-weight:500;">View Logs</a>|
        <a href="../user/recommendation.php" style="text-decoration:none; color:#333; font-weight:500;">Recommended Posts</a>|
      <a href="../user/liked_post.php" style="text-decoration:none; color:#333; font-weight:500;">Liked Posts</a>|


    <!-- User Section -->
    <div class="user-section" style="position:relative;">
        <div id="profile-container" style="display:inline-block; position:relative;">
            <img src="../assets/images/myaccount.png" alt="Account" id="profile-icon" tabindex="0" style="width:36px; height:36px; cursor:pointer; border-radius:50%; border:1px solid #ccc;">

            <div id="profile-dropdown" style="
                display:none;
                position:absolute;
                right:0;
                top:45px;
                background:#fff;
                border:1px solid #ccc;
                box-shadow:0 2px 8px rgba(0,0,0,0.15);
                padding:15px;
                width:300px;
                border-radius:8px;
                z-index:10000;
                font-family: Arial, sans-serif;
                font-size:14px;
            ">
                <p>Username: <?= htmlspecialchars($username) ?></p>
                <p>Email: <?= htmlspecialchars($email) ?></p>
                <p>Pregnancy Date: <?= htmlspecialchars($last_period) ?></p>
               
                <hr style="margin:10px 0;">
                <p style="display:flex; gap:10px;">
                    <a href="../user/profile.php" style="color:#333; text-decoration:none;">Update Profile</a>
                    <span style="border-left:1px solid black; height:16px; display:inline-block; vertical-align:middle; margin:0px 70px;"></span>
                    <a href="../auth/logout.php" style="color:#333; text-decoration:none;">Logout</a>
                </p>
            </div>
        </div>
    </div>
</div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileIcon = document.getElementById('profile-icon');
    const profileDropdown = document.getElementById('profile-dropdown');

    // Toggle dropdown on icon click
    profileIcon.addEventListener('click', function(event) {
        event.stopPropagation();
        profileDropdown.style.display = profileDropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Click outside closes dropdown
    document.addEventListener('click', function() {
        profileDropdown.style.display = 'none';
    });

    // Prevent click inside dropdown from closing
    profileDropdown.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});
</script>
