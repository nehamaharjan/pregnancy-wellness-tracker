<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = '';

// Get current user data to check last update time
$stmt = $conn->prepare("SELECT last_period, last_period_set_on FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$current_user_data = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $last_period_date = trim($_POST['last_period_date']); // Fixed field name

    // Validate username not empty
    if (empty($username)) {
        $errors[] = "Username cannot be empty.";
    } else {
        // Check if username is taken by others
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $username, $user_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Username already taken.";
        }
        $stmt->close();
    }

    // Validate last period date format if entered
    if (!empty($last_period_date)) {
        // Check if user is trying to change the date
        if ($current_user_data['last_period'] && $current_user_data['last_period'] !== $last_period_date) {
            // Check if it's been more than 60 days since last update
            $last_updated = $current_user_data['last_period_set_on'];
            if ($last_updated) {
                $last_updated_date = new DateTime($last_updated);
                $current_date = new DateTime();
                $days_since_update = $current_date->diff($last_updated_date)->days;
                
                if ($days_since_update > 60) {
                    $errors[] = "You cannot change your pregnancy date after 60 days of the last update.";
                }
            }
        }
        
        if (empty($errors)) { // Only proceed with other validations if 60-day check passes
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $last_period_date)) {
                $errors[] = "Last period date must be in YYYY-MM-DD format.";
            } else {
                // Additional validation: check if it's a valid date
                $date_parts = explode('-', $last_period_date);
                if (!checkdate($date_parts[1], $date_parts[2], $date_parts[0])) {
                    $errors[] = "Please enter a valid date.";
                } else {
                    // Date range validation
                    $input_date = new DateTime($last_period_date);
                    $current_date = new DateTime();
                    $nine_months_ago = new DateTime();
                    $nine_months_ago->modify('-9 months');
                    
                    // Check if date is in the future
                    if ($input_date > $current_date) {
                        $errors[] = "Last period date cannot be in the future.";
                    }
                    
                    // Check if date is more than 9 months ago
                    if ($input_date < $nine_months_ago) {
                        $errors[] = "Last period date cannot be more than 9 months ago.";
                    }
                }
            }
        }
    }

    // Password validation removed - password is now view-only

    if (empty($errors)) {
        // Convert empty date to NULL to avoid 0000-00-00
        $last_period_value = empty($last_period_date) ? null : $last_period_date;
        
        // Update the last_period_updated timestamp if the date is being changed
        $update_timestamp = ($current_user_data['last_period'] !== $last_period_date) ? true : false;
        
        // Update query without password field - password is not being updated at all
        if ($update_timestamp) {
            $stmt = $conn->prepare("UPDATE users SET username = ?, last_period = ?, last_period_set_on = NOW() WHERE id = ?");
            $stmt->bind_param("ssi", $username, $last_period_value, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ?, last_period = ? WHERE id = ?");
            $stmt->bind_param("ssi", $username, $last_period_value, $user_id);
        }

        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            // Store empty string for display instead of null
            $_SESSION['last_period'] = empty($last_period_date) ? '' : $last_period_date;
            $success = "Profile updated successfully.";
        } else {
            $errors[] = "Failed to update profile: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    // Redirect if accessed directly
    header("Location: ../user/profile.php");
    exit();
}

// Store messages in session to show after redirect
$_SESSION['profile_errors'] = $errors;
$_SESSION['profile_success'] = $success;

header("Location: ../user/profile.php");
exit();
?>