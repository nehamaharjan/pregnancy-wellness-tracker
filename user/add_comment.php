<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if(isset($_POST['add_comment'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user_id'];
    $comment_text = $_POST['comment_text'];

    $image_name = null;
    if(isset($_FILES['comment_image']) && $_FILES['comment_image']['error'] == 0){
        $image_name = time() . '_' . $_FILES['comment_image']['name'];
        move_uploaded_file($_FILES['comment_image']['tmp_name'], "../uploads/$image_name");
    }

    $link = !empty($_POST['comment_link']) ? $_POST['comment_link'] : null;

    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment_text, image, link, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iisss", $post_id, $user_id, $comment_text, $image_name, $link);
    $stmt->execute();
    $stmt->close();

    header("Location: discussion_board.php"); // Redirect back
}
?>
