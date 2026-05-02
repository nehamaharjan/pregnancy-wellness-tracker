<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}
include('../includes/db.php');

$user_id = $_SESSION['user_id'];
$search = '';
$no_results = false;

if(isset($_GET['q']) && !empty(trim($_GET['q']))){
    $search = trim($_GET['q']);
    $stmt = $conn->prepare("
        SELECT p.*, u.username 
        FROM posts p 
        JOIN users u ON p.user_id = u.id
        WHERE p.title LIKE CONCAT('%', ?, '%') OR p.content LIKE CONCAT('%', ?, '%')
        ORDER BY p.created_at DESC
    ");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $posts = $stmt->get_result();

    if($posts->num_rows == 0){
        $no_results = true;
    }
} else {
    $posts = $conn->query("
        SELECT p.*, u.username 
        FROM posts p 
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
    ");
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Discussion Board</title>
    <?php include('../includes/header.php'); ?>
    <link rel="stylesheet" href="../assets/css/discussion.css" />
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
   
</head>
<body>

<div class="discussion-header">
    <h2>Community Discussion Board</h2>
    <a href="create_post.php">+ What's On Your Mind?</a>
</div>
<div class="search-container" >
    <form method="GET" action="discussion_board.php">
        <input type="text" name="q" placeholder="Search posts..." style="width:70%; padding:8px; border-radius:5px; border:1px solid #ccc;">
        <button type="submit" style="padding:8px 16px; background: rgb(241, 189, 250); color:#f83939; border:none; border-radius:5px;">Search</button>
    </form>
</div>
<hr>

<?php if($no_results): ?>
    <p style="text-align:center; color:#555; margin:20px;">No posts found for "<b><?= htmlspecialchars($search) ?></b>"</p>
<?php endif; ?>

<?php while ($post = $posts->fetch_assoc()): ?>
<div class="post">
    <!-- DELETE BUTTON -->

    <?php if($post['user_id'] == $user_id): ?>
    <form method="POST" action="delete_post.php" class="delete-form" style="background:none;box-shadow:none; margin-left:700px; padding:0; display:flex;">
        <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
       <button type="submit" class="like-btn delete-btn" title="Delete post" style="margin-top:-5px;"> Delete</button>
    </form>
    <?php endif; ?>
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
    // Like button
    $post_id = $post['post_id'];
    $like_check = $conn->prepare("SELECT id FROM likes WHERE post_id=? AND user_id=?");
    $like_check->bind_param("ii", $post_id, $user_id);
    $like_check->execute();
    $like_check->store_result();
    $liked = $like_check->num_rows > 0;
    $like_check->close();

    $likes_res = $conn->prepare("SELECT COUNT(*) FROM likes WHERE post_id=?");
    $likes_res->bind_param("i", $post_id);
    $likes_res->execute();
    $likes_res->bind_result($likes_count);
    $likes_res->fetch();
    $likes_res->close();
    ?>
   <button class="like-btn" data-post="<?= $post_id ?>">
    <?= $liked ? "💖 Liked" : "🤍 Like" ?> (<span class="like-count"><?= $likes_count ?></span>)
</button>



    <div class="comments-section">
        <?php
        $post_id = $post['post_id'];
        $comments_res = $conn->query("
            SELECT c.*, u.username 
            FROM comments c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.post_id = $post_id 
            ORDER BY c.created_at ASC
        ");
        while ($comment = $comments_res->fetch_assoc()):
        ?>
        <div class="comment">
            <p><b><?= htmlspecialchars($comment['username']) ?>:</b> <?= nl2br(htmlspecialchars($comment['comment_text'])) ?></p>
            <?php if ($comment['image']): ?>
                <img src="../uploads/<?= htmlspecialchars($comment['image']) ?>" width="150"><br>
            <?php endif; ?>
            <?php if ($comment['link']): ?>
                <a href="<?= htmlspecialchars($comment['link']) ?>" target="_blank">🔗 Visit Link</a>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Comment toggle link -->
    <a class="toggle-comment" data-post="<?= $post['post_id'] ?>">💬 Add Comment</a>

    <!-- Comment form (hidden by default) -->
    <div class="comment-form-container" id="comment-form-<?= $post['post_id'] ?>">
        <form action="add_comment.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
            <textarea name="comment_text" placeholder="Write a comment..." required></textarea><br>
            <input type="file" name="comment_image" accept="image/*"><br>
            <input type="url" name="comment_link" placeholder="Add link (optional)"><br>
            <button type="submit" name="add_comment">Comment</button>
        </form>
    </div>
</div>
<hr>
<?php endwhile; ?>

<script>
document.querySelectorAll('.toggle-comment').forEach(btn => {
    btn.addEventListener('click', () => {
        const postId = btn.dataset.post;
        const form = document.getElementById('comment-form-' + postId);

        if (form.style.maxHeight && form.style.maxHeight !== "0px") {
            form.style.maxHeight = "0";
        } else {
            form.style.maxHeight = form.scrollHeight + "px";
        }
    });
});

document.querySelectorAll('.like-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const postId = btn.dataset.post;
        const countSpan = btn.querySelector('.like-count');

        fetch('like.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'post_id=' + postId
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'liked'){
                btn.style.color = '#6c63ff'; // change color when liked
            } else {
                btn.style.color = 'black'; // back to normal
            }
            countSpan.textContent = data.count; // update like count
        })
        .catch(err => console.error(err));
    });
});


 
</script>

</body>
</html>
