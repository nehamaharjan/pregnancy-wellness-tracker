<?php

session_start();
include '../includes/db.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !=='admin'){
    header("Location: ../auth/login.php"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - PregPal</title>
    <!-- AdminLTE CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Sidebar -->
    <?php include 'include/slidebar.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <h1 class="m-0">Welcome, <?php echo $_SESSION['username']; ?></h1>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                 <!-- Total Users -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-info">
        <div class="inner">
            <?php
            $res = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='user'");
            if($res){
                $count = $res->fetch_assoc();
                $totalUsers = $count['total'] ?? 0;
            } else {
                $totalUsers = 0;
            }
            ?>
            <h3><?php echo $totalUsers; ?></h3>
            <p>Total Users</p>
        </div>
        <div class="icon">
            <i class="fas fa-users"></i>
        </div>
        <a href="manage-users.php" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>

<!-- Insights Box -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-success">
        <div class="inner">
            <?php
            $res2 = $conn->query("SELECT COUNT(*) AS total FROM insights");
            $insightsCount = ($res2 && $res2->num_rows > 0) ? $res2->fetch_assoc()['total'] : 0;
            ?>
            <h3><?php echo $insightsCount; ?></h3>
            <p>Total Insights</p>
        </div>
        <div class="icon">
            <i class="fas fa-lightbulb"></i>
        </div>
        <a href="manage-insights.php" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>

<!-- Most Common Symptoms -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-primary">
        <div class="inner">
            <?php
            // Total number of symptoms logged
            $totalLogsQuery = $conn->query("SELECT COUNT(*) AS total FROM user_symptoms WHERE symptoms_text IS NOT NULL AND symptoms_text != ''");
            $totalLogs = ($totalLogsQuery && $totalLogsQuery->num_rows > 0) ? $totalLogsQuery->fetch_assoc()['total'] : 0;
            ?>
            <h3><?php echo $totalLogs; ?></h3>
            <p>Symptoms Logged</p>
        </div>
        <div class="icon">
            <i class="fas fa-notes-medical"></i>
        </div>
        <a href="symptom-stats.php" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>


<!-- Emails Sent -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
        <div class="inner">
            <?php
            // Count all sent emails
            $res5 = $conn->query("SELECT COUNT(*) AS total FROM email_reminders WHERE status='sent'");
            $emailsSent = ($res5 && $res5->num_rows > 0) ? $res5->fetch_assoc()['total'] : 0;
            ?>
            <h3><?php echo $emailsSent; ?></h3>
            <p>Emails Sent</p>
        </div>
        <div class="icon">
            <i class="fas fa-envelope"></i>
        </div>
        <a href="send-emails.php" class="small-box-footer">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>

        </section>
    </div>

    <!-- Footer -->
 

</div>

<!-- AdminLTE Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</html>