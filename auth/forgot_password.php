<?php
session_start();
require '../includes/db.php';
require '../includes/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require '../includes/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require '../includes/PHPMailer-master/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $errors[] = "Please enter your email.";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $username);
            $stmt->fetch();

            $otp = rand(100000, 999999);
            $otp_expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // Save OTP to DB
            $update = $conn->prepare("UPDATE users SET otp = ?, otp_expires = ? WHERE id = ?");
            $update->bind_param("ssi", $otp, $otp_expires, $user_id);
            $update->execute();

            // Send email with OTP
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'yourgmail@gmail.com';
                $mail->Password = 'yourpassword';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('yourgmail.com', 'PregPal');
                $mail->addAddress($email, $username);
                $mail->isHTML(true);
                $mail->Subject = 'PregPal Password Reset OTP';
                $mail->Body = "Hello $username, <br>Your OTP for password reset is <strong>$otp</strong>. <br> It will expire in 15 minutes.";

                $mail->send();
                $_SESSION['reset_email'] = $email;
                $success = "OTP sent! Please check your email.";
                header("Refresh: 2; url=reset_password.php");
                exit();
            } catch (Exception $e) {
                $errors[] = "Email sending failed: " . $mail->ErrorInfo;
            }
        } else {
            $errors[] = "No account with that email.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-wrapper" style="background: linear-gradient(rgba(255, 229, 212, 0.88), rgba(255, 229, 212, 0.88)),
            url('../assets/images/formbg.jpg') no-repeat center center;
            background-size: cover; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
             <div class="form-container" style="background-color: white;">

    <?php if ($errors): ?>
        <div class="error-box">
            <?php foreach ($errors as $e): ?>
                <p><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-box">
            <p><?= htmlspecialchars($success) ?></p>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Enter your registered email:</label>
        <input type="email" name="email" required>
        <button type="submit">Send OTP</button>
    </form>
</div>
</body>
</html>
