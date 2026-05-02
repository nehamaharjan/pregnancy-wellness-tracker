<?php
session_start();
require '../includes/db.php';

$errors = [];
$success = '';

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate fields
    if (empty($otp) || empty($new_password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } elseif (strlen($new_password) < 8 || !preg_match('/[\W]/', $new_password)) {
        $errors[] = "Password must be at least 8 characters and include a special character.";
    } else {
        // Check OTP
        $stmt = $conn->prepare("SELECT id, otp, otp_expires FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $db_otp, $otp_expires);
            $stmt->fetch();

            if ($otp !== $db_otp) {
                $errors[] = "Invalid OTP.";
            } elseif (strtotime($otp_expires) < time()) {
                $errors[] = "OTP has expired.";
            } else {
                // Update password (plain text as per your setup)
                $update = $conn->prepare("UPDATE users SET password = ?, otp = NULL, otp_expires = NULL WHERE id = ?");
                $update->bind_param("si", $new_password, $user_id);
                if ($update->execute()) {
                    unset($_SESSION['reset_email']);
                    $success = "Password reset successful!";
                    header("Refresh: 3; url=login.php");
                    exit();
                } else {
                    $errors[] = "Failed to reset password.";
                }
            }
        } else {
            $errors[] = "User not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password | PregPal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-wrapper" style="background: linear-gradient(rgba(255, 229, 212, 0.88), rgba(255, 229, 212, 0.88)),
            url('../assets/images/formbg.jpg') no-repeat center center;
            background-size: cover; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
             <div class="form-container" style="background-color: white;">
   
    <h2>Reset Password</h2>

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
        <label>Enter OTP:</label>
        <input type="text" name="otp" required>

        <label>New Password:</label>
        <input type="password" name="new_password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit">Reset Password</button>
    </form>
</div>
<?php if ($success): ?>
<script>
    setTimeout(() => {
        window.location.href = 'login.php';
    }, 3000);
</script>
<?php endif; ?>
</body>
</html>
