<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

// PHPMailer
require '../includes/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require '../includes/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require '../includes/PHPMailer-master/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Send pending email manually
// if(isset($_GET['send_now'])){
//     $reminder_id = intval($_GET['send_now']);

//     $reminder = $conn->query("
//         SELECT er.*, u.email, u.username
//         FROM email_reminders er
//         JOIN users u ON er.user_id = u.id
//         WHERE er.reminder_id = $reminder_id
//     ")->fetch_assoc();

//     if($reminder && $reminder['status'] === 'pending'){
//         $mail = new PHPMailer(true);
//         try {
//             $mail->isSMTP();
//             $mail->Host = 'smtp.gmail.com';
//             $mail->SMTPAuth = true;
//             $mail->Username = 'maharjannehaa@gmail.com';
//             $mail->Password = 'xcnbswhhkvejadut';
//             $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//             $mail->Port = 587;

//             $mail->setFrom('maharjannehaa@gmail.com', 'PregPal Admin');
//             $mail->addAddress($reminder['email'], $reminder['username']);
//             $mail->isHTML(true);
//             $mail->Subject = $reminder['email_subject'];
//             $mail->Body = $reminder['email_body'];

//             $mail->send();

//             $conn->query("UPDATE email_reminders SET status='sent' WHERE reminder_id=".$reminder['reminder_id']);
//         } catch (Exception $e) {
//             error_log("Failed to send email: ".$mail->ErrorInfo);
//         }
//         header("Location: send-emails.php");
//         exit;
//     }
// }

// Generate weekly reminders for all users
if(isset($_POST['send_weekly'])){
    $today = new DateTime();
    $result = $conn->query("SELECT id, username, email, last_period FROM users WHERE role='user'");
    
    while ($user = $result->fetch_assoc()) {
        $user_id = $user['id'];
        $username = $user['username'];
        $email = $user['email'];
        $last_period = $user['last_period'];
        if (!$last_period) continue;

        $lp = new DateTime($last_period);
        $interval = $lp->diff($today);
        $weeks = floor($interval->days / 7);
        $days = $interval->days % 7;

        // Check if reminder exists
        $stmt = $conn->prepare("SELECT reminder_id FROM email_reminders WHERE user_id = ? AND pregnancy_week = ?");
        $stmt->bind_param("ii", $user_id, $weeks);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows === 0){
            $subject = "PregPal Weekly Reminder: Week $weeks";
            $body = "Hi $username,<br>You are in <strong>Week $weeks</strong> of your pregnancy ($days days).<br>Don't forget to log your symptoms!";
            $scheduled_date = $today->format('Y-m-d');
            $status = 'pending';

            $insert = $conn->prepare("INSERT INTO email_reminders (user_id, pregnancy_week, email_subject, email_body, scheduled_date, status) VALUES (?, ?, ?, ?, ?, ?)");
            $insert->bind_param("iissss", $user_id, $weeks, $subject, $body, $scheduled_date, $status);
            $insert->execute();
            $insert->close();
        }
        $stmt->close();
    }
    header("Location: send-emails.php");
    exit;
}

// Stats
$sentCount = $conn->query("SELECT COUNT(*) as total FROM email_reminders WHERE status='sent'")->fetch_assoc()['total'];
$pendingCount = $conn->query("SELECT COUNT(*) as total FROM email_reminders WHERE status='pending'")->fetch_assoc()['total'];

// Fetch reminders
$reminders = $conn->query("
    SELECT er.*, u.username, u.email 
    FROM email_reminders er
    JOIN users u ON er.user_id = u.id
    ORDER BY er.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Emails - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php include 'include/slidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Email Reminders</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- Stats -->
                <div class="row mb-3">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner"><h3><?php echo $sentCount; ?></h3><p>Emails Sent</p></div>
                            <div class="icon"><i class="fas fa-envelope"></i></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner"><h3><?php echo $pendingCount; ?></h3><p>Pending Emails</p></div>
                            <div class="icon"><i class="fas fa-clock"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Generate Weekly Reminders -->
                <!-- <form method="POST" class="mb-3">
                    <button type="submit" name="send_weekly" class="btn btn-warning">
                        Generate & Send Weekly Reminders
                    </button>
                </form> -->

                <!-- Email Table -->
                <div class="card">
                    <div class="card-header"><h3 class="card-title">All Email Reminders</h3></div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Pregnancy Week</th>
                                    <th>Subject</th>
                                    <th>Scheduled Date</th>
                                    <th>Status</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $reminders->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['username'].' ('.$row['email'].')'); ?></td>
                                    <td><?php echo $row['pregnancy_week']; ?></td>
                                    <td><?php echo htmlspecialchars($row['email_subject']); ?></td>
                                    <td><?php echo $row['scheduled_date']; ?></td>
                                    <td><?php echo ucfirst($row['status']); ?></td>

                     <!-- <td>
                     <?php if($row['status'] === 'pending'): ?>
                     <a href="send-emails.php?send_now=<?php echo $row['reminder_id']; ?>" class="btn btn-sm btn-primary">Send Now</a>
                    <?php endif; ?>
                    </td> -->

                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
