<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}

include('../includes/db.php');

$user_id = $_SESSION['user_id'];

// Step 1: Fetch tags from posts the user liked
$liked_tags = [];
$liked_posts = $conn->query("SELECT p.tags 
                             FROM posts p 
                             JOIN likes l ON p.post_id = l.post_id 
                             WHERE l.user_id = $user_id");

while ($row = $liked_posts->fetch_assoc()) {
    if ($row['tags']) {
        $tags = array_map('trim', explode(',', $row['tags']));
        $liked_tags = array_merge($liked_tags, $tags);
    }
}

// Remove duplicates
$liked_tags = array_unique($liked_tags);

// Step 2: Fetch recommended posts based on matching tags
$recommended_posts = [];

if (!empty($liked_tags)) {
    $like_conditions = [];
    foreach ($liked_tags as $tag) {
        $tag_safe = $conn->real_escape_string($tag);
        $like_conditions[] = "tags LIKE '%$tag_safe%'";
    }

    $where_clause = implode(' OR ', $like_conditions);

    $sql = "SELECT * FROM posts WHERE ($where_clause) AND post_id NOT IN 
            (SELECT post_id FROM likes WHERE user_id = $user_id)
            ORDER BY created_at DESC LIMIT 10";

    $result = $conn->query($sql);
    while ($post = $result->fetch_assoc()) {
        $recommended_posts[] = $post;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recommended Posts</title>
     <?php include('../includes/header.php');  ?>
      <link rel="stylesheet" href="../assets/css/discussion.css" />
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
</head>
<body>

<div class="discussion-header">
    <h2>Recommended For You</h2>
</div>
<hr>

<?php if (empty($recommended_posts)): ?>
    <p style="text-align:center;">No recommendations yet. Like or interact with posts to get recommendations!</p>
<?php else: ?>
    <?php foreach ($recommended_posts as $post): ?>
        <div class="post">
            <h3><?= htmlspecialchars($post['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            <?php if ($post['image']): ?>
                <img src="../uploads/<?= htmlspecialchars($post['image']) ?>" width="250"><br>
            <?php endif; ?>
            <?php if ($post['link']): ?>
                <a href="<?= htmlspecialchars($post['link']) ?>" target="_blank">🔗 Visit Link</a><br>
            <?php endif; ?>
            <!-- <p><b>Tags:</b> <?= htmlspecialchars($post['tags']) ?></p> -->
            <a href="view_post.php?post_id=<?= $post['post_id'] ?>">💬 Comment</a>
        </div>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
