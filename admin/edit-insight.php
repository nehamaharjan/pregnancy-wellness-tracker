<?php

session_start();
include '../includes/db.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !=='admin'){
    header("Location: ../auth/login.php"); 
    exit;
}

// Get insight ID
if(!isset($_GET['id'])) {
    header("Location: manage-insights.php");
    exit;
}

$id = intval($_GET['id']);

// Handle update form
if(isset($_POST['update_insight'])){
    $trimester = $_POST['trimester'];
    $title = $_POST['title'];
    $url = $_POST['url']; // name of the form field
   

    $stmt = $conn->prepare("UPDATE insights SET trimester=?, title=?, url=? WHERE insight_id=?");

if(!$stmt){
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("issi", $trimester, $title, $url, $id);
$stmt->execute();
$stmt->close();


    header("Location: manage-insights.php");
    exit;
}

// Fetch current insight data
$stmt = $conn->prepare("SELECT * FROM insights WHERE insight_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$insight = $result->fetch_assoc();
$stmt->close();

if(!$insight){
    echo "Insight not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Insight - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include 'include/slidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Edit Insight</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Insight</h3>
                    </div>
                    <form method="POST">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Trimester</label>
                                <select name="trimester" class="form-control" required>
                                    <option value="1" <?php if($insight['trimester']==1) echo 'selected'; ?>>First Trimester</option>
                                    <option value="2" <?php if($insight['trimester']==2) echo 'selected'; ?>>Second Trimester</option>
                                    <option value="3" <?php if($insight['trimester']==3) echo 'selected'; ?>>Third Trimester</option>
                                  
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($insight['title']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Content</label>
                                <textarea name="url" class="form-control" rows="4" required><?php echo htmlspecialchars($insight['url']); ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="update_insight" class="btn btn-success">Update Insight</button>
                            <a href="manage-insights.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
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
