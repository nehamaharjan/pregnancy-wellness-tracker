<?php
// session_start();
// require '../includes/db.php';
// header('Content-Type: application/json');

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
//     echo json_encode(['First'=>0,'Second'=>0,'Third'=>0]);
//     exit();
// }

// $user_id = $_SESSION['user_id'];

// // Fetch user's last period
// $stmt = $conn->prepare("SELECT last_period FROM users WHERE id = ?");
// $stmt->bind_param("i", $user_id);
// $stmt->execute();
// $stmt->bind_result($last_period);
// $stmt->fetch();
// $stmt->close();

// if (!$last_period) {
//     echo json_encode(['First'=>0,'Second'=>0,'Third'=>0]);
//     exit();
// }

// $lp = new DateTime($last_period);

// // Fetch all symptoms for this user
// $stmt = $conn->prepare("SELECT symptom_date, symptoms_text FROM user_symptoms WHERE user_id = ?");
// $stmt->bind_param("i", $user_id);
// $stmt->execute();
// $res = $stmt->get_result();

// $trimester_count = ['First' => 0, 'Second' => 0, 'Third' => 0];

// while ($row = $res->fetch_assoc()) {
//     $date = new DateTime($row['symptom_date']);
//     $weeks = floor($lp->diff($date)->days / 7);

//     // Determine trimester
//     if ($weeks <= 12) $trimester_count['First']++;
//     elseif ($weeks <= 27) $trimester_count['Second']++;
//     else $trimester_count['Third']++;
// }

// $stmt->close();
// $conn->close();

// echo json_encode($trimester_count);
