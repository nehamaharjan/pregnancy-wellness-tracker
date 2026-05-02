<?php

session_start();
include '../includes/db.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !=='admin'){
    header("Location: ../auth/login.php"); 
    exit;
}

// Handle adding new insight
if(isset($_POST['add_insight'])){
    $trimester = $_POST['trimester'];
    $title = $_POST['title'];
    $url = $_POST['url'];
    $summary = $_POST['summary']; // NEW

    $stmt = $conn->prepare("INSERT INTO insights (trimester, title, url, summary) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $trimester, $title, $url, $summary);
    $stmt->execute();
    $stmt->close();

    header("Location: manage-insights.php");
    exit;
}

// Fetch existing insights
$insights = $conn->query("SELECT * FROM insights ORDER BY trimester ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Insights - Admin</title>
    <!-- AdminLTE CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php include  'include/slidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Manage Insights</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <!-- Add New Insight Form -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Add New Insight</h3>
                    </div>
                    <form method="POST">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Trimester</label>
                                <select name="trimester" class="form-control" required>
                                    <option value="1">First Trimester</option>
                                    <option value="2">Second Trimester</option>
                                    <option value="3">Third Trimester</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Link</label>
                                <textarea name="url" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Summary</label>
                                <textarea name="summary" class="form-control" rows="4"></textarea>
                            </div>
                            
                            <div class="card-footer">
                                <button type="submit" name="add_insight" class="btn btn-success">Add Insight</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Existing Insights Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Existing Insights</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Trimester</th>
                                    <th>Title</th>
                                    <th>Link</th>
                                    <th>Summary</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($insights && $insights->num_rows > 0): ?>
                                    <?php while($row = $insights->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $row['insight_id'] ?></td>
                                            <td><?= $row['trimester'] ?></td>
                                            <td><?= htmlspecialchars($row['title']) ?></td>

                                            <!-- Link / Video -->
                                            <td>
                                                <?php 
                                                    $url = $row['url'];
                                                    if (preg_match('/(youtube\.com|youtu\.be)/', $url)) {
                                                        if (strpos($url, 'watch?v=') !== false) {
                                                            $video_id = explode("v=", $url)[1];
                                                            $video_id = explode("&", $video_id)[0];
                                                        } elseif (strpos($url, 'youtu.be/') !== false) {
                                                            $video_id = explode("youtu.be/", $url)[1];
                                                            $video_id = explode("?", $video_id)[0];
                                                        } else { $video_id = ''; }

                                                        if ($video_id) {
                                                            echo "<iframe width='250' height='140' 
                                                                    src='https://www.youtube.com/embed/$video_id' 
                                                                    frameborder='0' allowfullscreen></iframe>";
                                                        } else {
                                                            echo "<a href='$url' target='_blank'>$url</a>";
                                                        }
                                                    } else {
                                                        echo "<a href='$url' target='_blank'>$url</a>";
                                                    }
                                                ?>
                                            </td>

                                            <!-- Summary -->
                                            <td><?= htmlspecialchars($row['summary']) ?></td>

                                            <td>
                                                <a href="edit-insight.php?id=<?= $row['insight_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                                <a href="delete-insight.php?id=<?= $row['insight_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No insights found.</td>
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

<!-- AdminLTE Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
