
<?php
session_start();
include '../includes/db.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !=='admin'){
    header("Location: ../auth/login.php"); 
    exit;
}

// Handle Delete Post
if(isset($_GET['delete-post'])){
    $post_id = intval($_GET['delete-post']);
    $conn->query("DELETE FROM posts WHERE post_id = $post_id");
    $conn->query("DELETE FROM comments WHERE post_id = $post_id");
    $conn->query("DELETE FROM likes WHERE post_id = $post_id");
    header("Location: manage-post.php");
    exit;
}

// Fetch all posts with counts
$posts = $conn->query("
    SELECT p.*, u.username,
           (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.post_id) AS like_count,
           (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.post_id) AS comment_count
    FROM posts p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Posts - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php include 'include/slidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Manage Posts</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <!-- Posts Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All User Posts</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Likes</th>
                                    <th>Comments</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($posts && $posts->num_rows > 0): ?>
                                    <?php while($row = $posts->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $row['post_id'] ?></td>
                                            <td><?= htmlspecialchars($row['title']) ?></td>
                                            <td><?= htmlspecialchars($row['username']) ?></td>
                                            <td><?= $row['like_count'] ?></td>
                                            <td><?= $row['comment_count'] ?></td>
                                            <td><?= $row['created_at'] ?></td>
                                            <td>
                                                <a href="view-post.php?post_id=<?= $row['post_id'] ?>" class="btn btn-sm btn-info">View</a>
 <a href="manage-post.php?delete-post=<?= $row['post_id'] ?>" class="btn btn-sm btn-danger" 
 onclick="return confirm('Are you sure you want to delete this post? All comments will be deleted too.')">Delete</a>                                        </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No posts found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
