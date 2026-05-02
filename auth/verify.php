<?php
include('../includes/db.php');
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $otp = trim($_POST['otp']);

    $stmt = $conn->prepare("SELECT id, otp_expires FROM users WHERE email = ? AND otp = ? AND is_verified = 0");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (new DateTime() <= new DateTime($user['otp_expires'])) {
            $update = $conn->prepare("UPDATE users SET is_verified = 1, otp = NULL, otp_expires = NULL WHERE id = ?");
            $update->bind_param("i", $user['id']);
            $update->execute();

            $success = "Your email has been verified successfully! You can now login.";
        } else {
            $errors[] = "OTP expired. Please register again.";
        }
    } else {
        $errors[] = "Invalid OTP or email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP | PregPal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="form-wrapper" style="background: linear-gradient(rgba(255, 229, 212, 0.88), rgba(255, 229, 212, 0.88)),
            url('../assets/images/formbg.jpg') no-repeat center center;
            background-size: cover; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="form-container" style="background-color: white;">
    <h2>Verify Your Email</h2>

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
            <p><a href="login.php">Click here to login</a></p>
        </div>
    <?php else: ?>
        <form method="POST" action="">
            <label>Email:</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

            <label>OTP Code:</label>
            <input type="text" name="otp" required maxlength="6">

            <button type="submit">Verify OTP</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
