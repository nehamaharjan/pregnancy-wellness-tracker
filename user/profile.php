<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user data from DB
$stmt = $conn->prepare("SELECT username, email, last_period, password, last_period_set_on FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$username = $user['username'];
$email = $user['email'];
// Handle NULL and 0000-00-00 dates properly
$last_period = ($user['last_period'] && $user['last_period'] !== '0000-00-00') ? $user['last_period'] : '';
$password = $user['password'] ?? '';
$last_period_set_on = $user['last_period_set_on'];

// Check if user can still edit the date (within 60 days)
$can_edit_date = true;
$days_remaining = 0;
if ($last_period_set_on) {
    $last_updated_date = new DateTime($last_period_set_on);
    $current_date = new DateTime();
    $days_since_update = $current_date->diff($last_updated_date)->days;
    
    if ($days_since_update > 60) {
        $can_edit_date = false;
    } else {
        $days_remaining = 90 - $days_since_update;
    }
}

$errors = $_SESSION['profile_errors'] ?? [];
$success = $_SESSION['profile_success'] ?? '';

unset($_SESSION['profile_errors'], $_SESSION['profile_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Update Profile | PregPal</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <style>
        .form-wrapper {
            background: linear-gradient(rgba(255, 229, 212, 0.88), rgba(255, 229, 212, 0.88)),
                        url('../assets/images/formbg.jpg') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .form-container {
            max-width: 400px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .form-container h2 {
            text-align: center;
            color: #ff0000ff;
            margin-bottom: 20px;
        }
        
        .form-container label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }
        
        .form-container input:disabled {
            background-color: #f5f5f5;
            color: #666;
        }
        
        .password-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .password-row label {
            margin: 0;
            white-space: nowrap;
            min-width: 80px;
        }
        
        .password-row input[type="password"],
        .password-row input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }
        
        .toggle-password {
            cursor: pointer;
            font-size: 1.3em;
            user-select: none;
            padding: 5px;
            color: #666;
        }
        
        .toggle-password:hover {
            color: #333;
        }
        
        .change-password-link {
            display: block;
            text-decoration: none;
            color: #ff0000ff;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
        
        .change-password-link:hover {
            text-decoration: underline;
        }
        
        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 15px;
        }
        
      
        
        .form-container p {
            text-align: center;
            margin: 0;
        }
        
        .form-container a {
            color: #ff0008ff;
            text-decoration: none;
        }
        
        .form-container a:hover {
            text-decoration: underline;
        }
        
        .error-box {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .error-box p {
            margin: 5px 0;
            text-align: left;
        }
        
        .success-box {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .success-box p {
            margin: 5px 0;
            text-align: center;
        }
        
        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .warning-box p {
            margin: 5px 0;
            text-align: center;
        }
        
        .date-info {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }
        
        .disabled-field {
            position: relative;
        }
        
        .disabled-field input[disabled] {
            background-color: #f5f5f5 !important;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="form-wrapper">
        <div class="form-container">
            <h2>Update Profile</h2>

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

            <?php if (!$can_edit_date && $last_period): ?>
                <div class="warning-box">
                    <p><strong>Note:</strong> You cannot change your last period date as it has been more than 60 days since your last update.</p>
                </div>
            <?php elseif ($can_edit_date && $days_remaining <= 10 && $last_period): ?>
                <div class="warning-box">
                    <p><strong>Warning:</strong> You have <?= $days_remaining ?> days left to modify your last period date.</p>
                </div>
            <?php endif; ?>

            <form method="POST" action="profile_update.php">
                <label>Username:</label>
                <input type="text" name="username" required value="<?= htmlspecialchars($username) ?>" />

                <label>Email:</label>
                <input type="email" disabled value="<?= htmlspecialchars($email) ?>" />
                
                <div class="password-row">
                    <label for="password">Password:</label>
                
            
                    <input type="password" id="password" disabled value="<?= htmlspecialchars($password) ?>" />
                    <span class="toggle-password" onclick="togglePasswordVisibility()">👁️</span>
                </div>
                
                <a href="../auth/reset_password.php" class="change-password-link">Change Password</a>

                <label>Pregnancy Date:</label>
                <div class="<?= !$can_edit_date ? 'disabled-field' : '' ?>">
                    <input type="date" name="last_period_date" value="<?= htmlspecialchars($last_period) ?>" 
                           min="<?= date('Y-m-d', strtotime('-9 months')); ?>" 
                           max="<?= date('Y-m-d'); ?>"
                           <?= !$can_edit_date ? 'disabled' : '' ?> />
                    <?php if ($last_period_set_on): ?>
                        <div class="date-info">
                            Last updated: <?= date('M d, Y', strtotime($last_period_set_on)) ?>
                            <?php if ($can_edit_date): ?>
                                (<?= $days_remaining ?> days left to edit)
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" name="update_profile">Update</button>
            </form>

            <p><a href="home.php">Back to Home</a></p>
        </div>
    </div>
    
    <script>
        function togglePasswordVisibility() {
            const pwdInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                toggleIcon.textContent = '👁️';
            } else {
                pwdInput.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }

        // Add date validation feedback
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.querySelector('input[name="last_period_date"]');
            
            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    const currentDate = new Date();
                    const nineMonthsAgo = new Date();
                    nineMonthsAgo.setMonth(currentDate.getMonth() - 9);
                    
                    if (selectedDate > currentDate) {
                        alert('Last period date cannot be in the future.');
                        this.value = '';
                    } else if (selectedDate < nineMonthsAgo) {
                        alert('Last period date cannot be more than 9 months ago.');
                        this.value = '';
                    }
                });
            }
        });
    </script>
</body>
</html>