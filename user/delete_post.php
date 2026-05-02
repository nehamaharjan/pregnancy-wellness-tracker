<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}

include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'] ?? 0;

    if (!$post_id) {
        die("Invalid post ID.");
    }

    // DELETE query only if the user owns the post
    $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ? AND user_id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ii", $post_id, $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: discussion_board.php");
        exit();
    } else {
        die("Execute failed: " . $stmt->error);
    }
}
?>
