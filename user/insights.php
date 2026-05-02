<?php
require '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's last period to determine trimester
$stmt = $conn->prepare("SELECT last_period FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($last_period);
$stmt->fetch();
$stmt->close();

$today = new DateTime();
$weeks = 0;
$trimester = 1;

if ($last_period) {
    $lp = new DateTime($last_period);
    $interval = $lp->diff($today);
    $weeks = floor($interval->days / 7);

    if ($weeks >= 1 && $weeks <= 12) $trimester = 1;
    elseif ($weeks >= 13 && $weeks <= 27) $trimester = 2;
    elseif ($weeks >= 28) $trimester = 3;
}

// Fetch insights for this trimester
$insight_stmt = $conn->prepare("SELECT * FROM insights WHERE trimester = ? ORDER BY created_at DESC");
$insight_stmt->bind_param("i", $trimester);
$insight_stmt->execute();
$insight_res = $insight_stmt->get_result();
$insights = $insight_res->fetch_all(MYSQLI_ASSOC);
$insight_stmt->close();
?>

     

    <style>
        .discussion-header {
  width: 80%;
  max-width: 800px;
  margin: 40px auto 10px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.discussion-header h2 {
  margin: 0;
      text-align: center;
    color: #d44c2e;
    margin-bottom: 25px;
    font-size: 2rem;
    font-weight: bold;
}
.container a {
  text-decoration: none;
  color: rgb(236, 147, 251);
  transition: 0.3s;
}

.container a:hover {
  color: rgb(124, 49, 116);
}
        .section { margin: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; background: #f9f9f9; }
        .insight-article, .insight-video { margin-bottom: 20px; }
        .insight-video iframe { border-radius: 8px; width: 100%; height: 315px; }
    </style>
    <hr>
<div id="insights">
    <div class="discussion-header">
        <h2>Insights for Trimester <?= $trimester ?> </h2>
</div>
<hr>

    <div class="container">
    <?php if(count($insights) > 0): ?>
    <?php foreach($insights as $item): ?>
        <?php
            $url = trim($item['url'] ?? '');
            $title = htmlspecialchars($item['title']);
            $summary = htmlspecialchars($item['summary'] ?? '');

            // Detect YouTube
            $is_youtube = false;
            $video_id = '';

            if (!empty($url) && (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false)) {
                $is_youtube = true;

                if (strpos($url, 'watch?v=') !== false) {
                    $video_id = explode("v=", $url)[1];
                    $video_id = explode("&", $video_id)[0];
                } elseif (strpos($url, 'youtu.be/') !== false) {
                    $video_id = explode("youtu.be/", $url)[1];
                    $video_id = explode("?", $video_id)[0];
                }
            }
        ?>

        <div class="section" style="display:flex; gap:20px; flex-wrap:wrap;">
            <div style="flex:1; min-width:250px;">
                <h4><?= $title ?></h4>
                <?php if($is_youtube && $video_id): ?>
                    <iframe src="https://www.youtube.com/embed/<?= $video_id ?>" frameborder="0" allowfullscreen style="width:100%; height:200px; border-radius:8px;"></iframe>
                <?php else: ?>
                       <a href="<?= htmlspecialchars($url) ?>" target="_blank">Read more</a>
                <?php endif; ?>
            </div>
            <div style="border-left: 2px solid #000; height: 30px; margin: 0 20px;"></div>

            <div style="flex:1; min-width:250px;">
                <?php if(!empty($summary)): ?>
                    <p><?= $summary ?></p>
                <?php endif; ?>
            </div>
        </div>

    <?php endforeach; ?>
<?php else: ?>
    <p>No insights available for this trimester yet.</p>
<?php endif; ?>


</div>
    </div>
