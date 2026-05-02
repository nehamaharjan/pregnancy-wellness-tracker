<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}

include('../includes/db.php');
$user_id = $_SESSION['user_id'];

// Fetch all posts liked by this user
$liked_posts = $conn->prepare("
    SELECT p.*, u.username 
    FROM posts p
    JOIN users u ON p.user_id = u.id
    JOIN likes l ON l.post_id = p.post_id
    WHERE l.user_id = ?
    ORDER BY l.created_at DESC
");
$liked_posts->bind_param("i", $user_id);
$liked_posts->execute();
$result = $liked_posts->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Liked Posts</title>
      <?php include('../includes/header.php');  ?>
      <link rel="stylesheet" href="../assets/css/discussion.css" />
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
</head>
<body>

<div class="discussion-header">
    <h2>My Liked Posts</h2>
</div>
<hr>

<?php if($result->num_rows === 0): ?>
    <p style="text-align:center; margin-top:20px;">You haven't liked any posts yet.</p>
<?php endif; ?>

<?php while($post = $result->fetch_assoc()): ?>
<div class="post">
    <h3><?= htmlspecialchars($post['title']) ?></h3>
    <p><b>By:</b> <?= htmlspecialchars($post['username']) ?> | <?= $post['created_at'] ?></p>
    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>

    <?php if ($post['image']): ?>
        <img src="../uploads/<?= htmlspecialchars($post['image']) ?>" width="250"><br>
    <?php endif; ?>

    <?php if ($post['link']): ?>
        <a href="<?= htmlspecialchars($post['link']) ?>" target="_blank">🔗 Visit Link</a><br>
    <?php endif; ?>

    <?php
    // Show like count
    $like_count_res = $conn->prepare("SELECT COUNT(*) FROM likes WHERE post_id=?");
    $like_count_res->bind_param("i", $post['post_id']);
    $like_count_res->execute();
    $like_count_res->bind_result($like_count);
    $like_count_res->fetch();
    $like_count_res->close();
    ?>
    <p>💖 Likes: <?= $like_count ?></p>
</div>
<hr>
<?php endwhile; ?>

</body>
</html>
