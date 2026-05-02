<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !=='admin'){
    header("Location: ../auth/login.php"); 
    exit;
}

// Get post ID
if(!isset($_GET['post_id'])){
    header("Location: manage-posts.php");
    exit;
}

$post_id = intval($_GET['post_id']);

// Handle Delete Comment
if(isset($_GET['delete_comment'])){
    $comment_id = intval($_GET['delete_comment']);
    $conn->query("DELETE FROM comments WHERE comment_id = $comment_id");
    header("Location: view-post.php?post_id=$post_id");
    exit;
}

// Fetch post details
$post_stmt = $conn->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id WHERE p.post_id=?");
$post_stmt->bind_param("i", $post_id);
$post_stmt->execute();
$post_result = $post_stmt->get_result();
$post = $post_result->fetch_assoc();
$post_stmt->close();

// Fetch comments
$comments_stmt = $conn->prepare("
    SELECT c.*, u.username 
    FROM comments c 
    JOIN users u ON c.user_id = u.id 
    WHERE c.post_id=? 
    ORDER BY c.created_at ASC
");
$comments_stmt->bind_param("i", $post_id);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();
$comments_stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Post - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php include 'include/slidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>View Post</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><?= htmlspecialchars($post['title']) ?></h3>
                    </div>
                    <div class="card-body">
                        <p><b>By:</b> <?= htmlspecialchars($post['username']) ?> | <?= $post['created_at'] ?></p>
                        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>

                        <?php if($post['image']): ?>
                            <img src="../uploads/<?= htmlspecialchars($post['image']) ?>" width="250"><br>
                        <?php endif; ?>

                        <?php if($post['link']): ?>
                            <a href="<?= htmlspecialchars($post['link']) ?>" target="_blank">🔗 Visit Link</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Comments</h3>
                    </div>
                    <div class="card-body">
                        <?php if($comments_result && $comments_result->num_rows > 0): ?>
                            <?php while($comment = $comments_result->fetch_assoc()): ?>
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <p><b><?= htmlspecialchars($comment['username']) ?>:</b> <?= nl2br(htmlspecialchars($comment['comment_text'])) ?></p>
                                        <?php if($comment['image']): ?>
                                            <img src="../uploads/<?= htmlspecialchars($comment['image']) ?>" width="150"><br>
                                        <?php endif; ?>
                                        <?php if($comment['link']): ?>
                                            <a href="<?= htmlspecialchars($comment['link']) ?>" target="_blank">🔗 Visit Link</a><br>
                                        <?php endif; ?>
                                        <a href="view-post.php?post_id=<?= $post_id ?>&delete_comment=<?= $comment['comment_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this comment?')">Delete</a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No comments found.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </section>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
