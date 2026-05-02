<?php

session_start();
include '../includes/db.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !=='admin'){
    header("Location: ../auth/login.php"); 
    exit;
}

// Check if user ID is passed
if(isset($_GET['id'])){
    $user_id = intval($_GET['id']);

    // Delete user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role='user'");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to manage users
header("Location: manage-users.php");
exit;
