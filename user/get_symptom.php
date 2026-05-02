<?php
session_start();
require '../includes/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    echo json_encode([]);
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$date = $_GET['date'] ?? '';

if (!$date) {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("
    SELECT symptoms_text, diagnosis_html, created_at
    FROM user_symptoms
    WHERE user_id = ? AND DATE(symptom_date) = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("is", $user_id, $date);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();

echo json_encode($data);
?>
