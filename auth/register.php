<?php
require '../includes/PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require '../includes/PHPMailer-master/PHPMailer-master/src/SMTP.php';
require '../includes/PHPMailer-master/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('../includes/db.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long.";
} elseif (!preg_match('/[\W]/', $password)) {
    $errors[] = "Password must contain at least one special character.";
} else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Username or email already exist.";
        } else {
            $otp = rand(100000, 999999);
            $otp_expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // Insert user with OTP and expiry, is_verified = 0
            $insert = $conn->prepare("INSERT INTO users (username, email, password, role, otp, otp_expires, is_verified) VALUES (?, ?, ?, 'user', ?, ?, 0)");
            $insert->bind_param("sssss", $username, $email, $password, $otp, $otp_expires);

            if ($insert->execute()) {
                // Send OTP email via PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // SMTP config for Gmail
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'maharjannehaa@gmail.com';      // your Gmail
                    $mail->Password = 'xcnbswhhkvejadut';         // your Gmail App Password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('maharjannehaa@gmail.com', 'PregPal');
                    $mail->addAddress($email, $username);

                    $mail->isHTML(true);
                    $mail->Subject = ' PregPal OTP Code';
                    $mail->Body = "<p>Hello $username,</p><p>Your OTP code for PregPal Account is: <b>$otp</b></p>
                    <br><p>This code expires in 15 minutes.</p>";

                    $mail->send();

                    $success = "Registration successful! Check your email for the OTP to verify your account.";
                } catch (Exception $e) {
    $errors[] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}, Exception Message: " . $e->getMessage();
}

            } else {
                $errors[] = "Something went wrong during registration.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | PregPal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .error-box, .success-box {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .error-box {
            background-color: #f8d7da;
            color: #721c24;
        }
        .success-box {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
<div class="form-wrapper" style="background: linear-gradient(rgba(255, 229, 212, 0.88), rgba(255, 229, 212, 0.88)),
            url('../assets/images/formbg.jpg') no-repeat center center;
            background-size: cover; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="form-container" style="background-color: white;">
    <h2>Register</h2>

    <?php if ($errors): ?>
        <div class="error-box" id="errorBox">
            <?php foreach ($errors as $e): ?>
                <p><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    
    <?php if ($success): ?>
        <div class="success-box" id="successBox">
            <p><?= htmlspecialchars($success) ?></p>
            <p><a href="verify.php">Click here to verify your email</a></p>
        </div>
        <!-- <script>
            setTimeout(function () {
                window.location.href = 'login.php';
            }, 5000); // Redirect after 5 seconds
        </script> -->
    <?php else: ?>
        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

            <label>Email:</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>
    <?php endif; ?>

    <p>Already registered? <a href="login.php">Login here</a></p>
</div>
<script>
    // Auto-hide error messages after 5 seconds
    setTimeout(() => {
        const errorBox = document.getElementById('errorBox');
        if (errorBox) {
            errorBox.style.transition = "opacity 0.5s ease";
            errorBox.style.opacity = '0';
            setTimeout(() => {
                errorBox.style.display = 'none';
            }, 500);
        }
    }, 5000);
</script>
</body>
</html>
