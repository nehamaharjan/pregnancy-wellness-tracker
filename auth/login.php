<?php
session_start();
require '../includes/db.php';

$errors = [];
$success = '';

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        // Check user in database
        $stmt = $conn->prepare("SELECT id, username, password, role, is_verified FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $db_username, $db_password, $role, $is_verified);
            $stmt->fetch();

            if ($password === $db_password) { // no hashing
                if ($is_verified == 1) {
                    // Set session
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $db_username;
                    $_SESSION['role'] = $role;

                    // Redirect based on role
                    if ($role === 'admin') {
                        header("Location: ../admin/index.php");
                    } else {
                        header("Location: ../user/home.php");
                    }
                    exit();
                } else {
                    $errors[] = "Please verify your email before logging in.";
                }
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "No account found with that username.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | PregPal</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="form-wrapper" style="background: linear-gradient(rgba(255, 229, 212, 0.88), rgba(255, 229, 212, 0.88)),
            url('../assets/images/formbg.jpg') no-repeat center center;
            background-size: cover; min-height: 100vh; display: flex; align-items: center; justify-content: center;">

    <div class="form-container" style="background-color: white;">
        <h2>Login</h2>

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

        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

            <label>Password:</label>
            <input type="password" name="password" required>
            <p  style="text-align: right;"> <a href="forgot_password.php">Forgot Password</a></p>
            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

</body>
</html>
