<?php

require '../includes/db.php';
require '../includes/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require '../includes/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require '../includes/PHPMailer-master/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$today = new DateTime();

// Fetch all users
$result = $conn->query("SELECT id, username, email, last_period FROM users");

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

    // Check if reminder already exists for this week
    $stmt = $conn->prepare("SELECT reminder_id FROM email_reminders WHERE user_id = ? AND pregnancy_week = ?");
    $stmt->bind_param("ii", $user_id, $weeks);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // Insert new pending reminder
        $subject = "PregPal Weekly Reminder: Week $weeks";
        $body = "Hi $username,<br>You are in <strong>Week $weeks</strong> of your pregnancy ($days days).<br>Don't forget to log your symptoms!<br>";
        $scheduled_date = $today->format('Y-m-d');
        $status = 'pending';

        $insert = $conn->prepare("INSERT INTO email_reminders (user_id, pregnancy_week, email_subject, email_body, scheduled_date, status) VALUES (?, ?, ?, ?, ?, ?)");
        $insert->bind_param("iissss", $user_id, $weeks, $subject, $body, $scheduled_date, $status);
        $insert->execute();
        $insert->close();
    }
    $stmt->close();
}

// Send all pending reminders scheduled for today
$today_str = $today->format('Y-m-d');
$pending = $conn->query("SELECT * FROM email_reminders WHERE status = 'pending' AND scheduled_date <= '$today_str'");

while ($reminder = $pending->fetch_assoc()) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'maharjannehaa@gmail.com';
        $mail->Password = 'xcnbswhhkvejadut';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Fetch user email
        $stmt = $conn->prepare("SELECT email, username FROM users WHERE id = ?");
        $stmt->bind_param("i", $reminder['user_id']);
        $stmt->execute();
        $stmt->bind_result($email, $username);
        $stmt->fetch();
        $stmt->close();

        $mail->setFrom('maharjannehaa@gmail.com', 'PregPal');
        $mail->addAddress($email, $username);
        $mail->isHTML(true);
        $mail->Subject = $reminder['email_subject'];
        $mail->Body = $reminder['email_body'];

        $mail->send();

        // Mark as sent
        $update = $conn->prepare("UPDATE email_reminders SET status = 'sent' WHERE reminder_id = ?");
        $update->bind_param("i", $reminder['reminder_id']);
        $update->execute();
        $update->close();

    } catch (Exception $e) {
        error_log("Failed to send email: " . $mail->ErrorInfo);
    }
}
?>
