<?php
session_start();
include('../includes/db.php');

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];

$check = $conn->prepare("SELECT id FROM likes WHERE post_id=? AND user_id=?");
$check->bind_param("ii", $post_id, $user_id);
$check->execute();
$check->store_result();

if($check->num_rows > 0){
    // Unlike
    $del = $conn->prepare("DELETE FROM likes WHERE post_id=? AND user_id=?");
    $del->bind_param("ii", $post_id, $user_id);
    $del->execute();
    $status = 'unliked';
} else {
    // Like
    $ins = $conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
    $ins->bind_param("ii", $post_id, $user_id);
    $ins->execute();
    $status = 'liked';
}

// Return updated like count
$res = $conn->prepare("SELECT COUNT(*) FROM likes WHERE post_id=?");
$res->bind_param("i", $post_id);
$res->execute();
$res->bind_result($count);
$res->fetch();

echo json_encode(['status'=>$status,'count'=>$count]);
?>
