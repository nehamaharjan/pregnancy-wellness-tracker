<?php
session_start();
include '../includes/db.php';

// Only allow admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Check if post_id is provided
// Handle Delete Post
if(isset($_GET['delete-post'])){
    $post_id = intval($_GET['delete-post']);

    // Delete all comments associated with the post
    $conn->query("DELETE FROM comments WHERE post_id = $post_id");

    // Delete the post itself
    $conn->query("DELETE FROM posts WHERE post_id = $post_id");

    header("Location: manage-post.php");
    exit;
}

?>