<?php
// session_start();
// require '../includes/db.php';
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
//     header("Location: ../auth/login.php");
//     exit();
// }

// $diagnosis = "";
// $threshold = 1.0; // Minimum total score to consider a condition relevant

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $input_text = strtolower(trim($_POST['symptoms_text']));
//     if (empty($input_text)) {
//         $diagnosis = "Please enter your symptoms.";
//     } else {
//         // Fetch all symptoms from DB
//         $symptoms = [];
//         $result = $conn->query("SELECT symptom_id, symptom_name FROM symptoms");
//         while ($row = $result->fetch_assoc()) {
//             $symptoms[$row['symptom_id']] = strtolower($row['symptom_name']);
//         }

//         // Function to check whole word matching to avoid partial matches
//         function symptom_in_text($symptom, $text) {
//             // Use word boundaries \b to match whole words, escape special chars
//             $pattern = '/\b' . preg_quote($symptom, '/') . '\b/';
//             return preg_match($pattern, $text);
//         }

//         // Find matching symptom IDs from user input
//         $matched_symptom_ids = [];
//         foreach ($symptoms as $id => $name) {
//             if (strlen($name) > 2 && symptom_in_text($name, $input_text)) {
//                 $matched_symptom_ids[] = $id;
//             }
//         }

//         if (count($matched_symptom_ids) > 0) {
//             $ids_str = implode(',', $matched_symptom_ids);

//             // Query top 3 condition scores above threshold
//             $sql = "SELECT c.condition_name, c.severity, c.advice, SUM(sc.weight) as score
//                     FROM symptom_condition sc
//                     JOIN conditions c ON c.condition_id = sc.condition_id
//                     WHERE sc.symptom_id IN ($ids_str)
//                     GROUP BY c.condition_id
//                     HAVING score >= $threshold
//                     ORDER BY score DESC
//                     LIMIT 3";

//             $res = $conn->query($sql);
//             if ($res && $res->num_rows > 0) {
//                 $diagnosis = "<h3>Possible conditions based on your symptoms:</h3><ul>";
//                 while ($row = $res->fetch_assoc()) {
//                     $diagnosis .= "<li><strong>" . htmlspecialchars($row['condition_name']) . "</strong><br>";
//                     $diagnosis .= "Severity: " . htmlspecialchars($row['severity']) . "<br>";
//                     $diagnosis .= "Advice: " . htmlspecialchars($row['advice']) . "<br>";
//                     $diagnosis .= "Score: " . round($row['score'], 2) . "</li><br>";
//                 }
//                 $diagnosis .= "</ul>";
//             } else {
//                 $diagnosis = "No matching condition found with sufficient confidence.";
//             }
//         } else {
//             $diagnosis = "No symptoms matched from your input.";
//         }
//     }
// }
?>

