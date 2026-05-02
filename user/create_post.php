<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $link = $_POST['link'];
    $tags = $_POST['tags'];
    $image = '';

    // Handle image upload
 $imageError = '';
$image = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $image = basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($imageFileType, $allowed_extensions)) {
            $imageError = "Only image files (jpg, jpeg, png, gif, webp) are allowed.";
            $image = ''; // prevent saving invalid file
        } else {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image);
        }
    }
}


    $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, image, link, tags) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $title, $content, $image, $link, $tags);
    $stmt->execute();
    $stmt->close();

    header("Location: discussion_board.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
    <?php include('../includes/header.php'); ?>
    <link rel="stylesheet" href="../assets/css/dashboard.css" />
    <link rel="stylesheet" href="../assets/css/discussion.css" />
</head>
<body>
<h2>Create a Discussion Post</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Title:</label>
    <input type="text" name="title" required><br>

    <label>Content:</label>
    <textarea name="content" required></textarea><br>

    <label>Image:</label>
<input type="file" name="image" id="imageInput"><br>
<!-- Inline error message -->
<?php if(!empty($imageError)): ?>
    <span style="color:red; font-size:14px;"><?php echo $imageError; ?></span>
<?php endif; ?>


    <label>Link:</label>
    <input type="url" name="link"><br>

    <label>Tags (comma separated):</label>
    <input type="text" name="tags"><br>

    <button type="submit">Post</button>
</form>
</body>
</html>
