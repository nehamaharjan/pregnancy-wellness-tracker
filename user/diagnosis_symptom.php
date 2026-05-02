<?php
session_start();
require '../includes/db.php';
header('Content-Type: application/json');

// Enable errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check authentication
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$symptom_date = $_POST['symptom_date'] ?? '';
$input_text = trim($_POST['symptoms_text'] ?? '');
$threshold = 1.0;

if (!$symptom_date || $input_text === '') {
    echo json_encode(['status'=>'error','message'=>'Please select a date and enter your symptoms.']);
    exit();
}

try {
    if (!$conn) throw new Exception("Database connection missing.");

    // ----------------------------
    // 1) Fetch all known symptoms
    // ----------------------------
    $symptoms = [];
$res = $conn->query("SELECT symptom_id, symptom_name FROM symptoms");

if (!$res) {
    die(json_encode(["status"=>"error", "message"=>"Query failed: ".$conn->error]));
}

while ($row = $res->fetch_assoc()) {
    $symptoms[$row['symptom_id']] = strtolower(trim($row['symptom_name']));
}

// DEBUG
error_log("SYMPTOMS LOADED: " . json_encode($symptoms));

if (empty($symptoms)) {
    die(json_encode(["status"=>"error","message"=>"No symptoms in DB or wrong column/table name"]));
}

function symptom_in_text($symptom, $text) {
    $symptom = strtolower(trim($symptom));
    $text = strtolower($text);

    // 1. Exact match of whole words (headache, nausea, etc.)
    if (preg_match('/\b' . preg_quote($symptom, '/') . '\b/i', $text)) {
        return true;
    }

    // 2. Plural match for single-word symptoms
    if (preg_match('/\b' . preg_quote($symptom, '/') . 's?\b/i', $text)) {
        return true;
    }

    // 3. Handle multi-word symptoms ignoring spaces (back pain -> backpain)
    $normalized_text = str_replace(' ', '', $text);
    $normalized_symptom = str_replace(' ', '', $symptom);
    if (strpos($normalized_text, $normalized_symptom) !== false) {
        return true;
    }

    return false;
}


    // ----------------------------
    // 2) Match symptoms and calculate diagnosis
    // ------------------------
$matched_ids = [];
$lower_text = strtolower($input_text);

foreach ($symptoms as $id => $name) {
    if (symptom_in_text($name, $lower_text)) {
        $matched_ids[] = $id;
    }
}

$diagnosis = '';

if (count($matched_ids) > 0) {
    $ids_str = implode(',', array_map('intval', $matched_ids));

    // Use LEFT JOIN to ensure we get conditions even if weight < threshold
    $sql = "
        SELECT 
            c.condition_id, 
            c.condition_name, 
            c.severity, 
            c.advice, 
            COALESCE(SUM(sc.weight),0) AS score
        FROM conditions c
        LEFT JOIN symptom_condition sc 
            ON sc.condition_id = c.condition_id 
            AND sc.symptom_id IN ($ids_str)
        GROUP BY c.condition_id
        ORDER BY score DESC
        LIMIT 3
    ";

    $res2 = $conn->query($sql);
    if (!$res2) {
        throw new Exception("Diagnosis query failed: " . $conn->error);
    }

    if ($res2->num_rows > 0) {
        $diagnosis = "<h5>Possible conditions:</h5><ul>";
        while ($r = $res2->fetch_assoc()) {
            $diagnosis .= "<li><strong>" . htmlspecialchars($r['condition_name']) . "</strong><br>";
            $diagnosis .= "Severity: " . htmlspecialchars($r['severity']) . "<br>";
            $diagnosis .= "Advice: " . htmlspecialchars($r['advice']) . "</li><br>";
            
        }
        
        $diagnosis .= "</ul>";
    } else {
        $diagnosis = "No matching conditions found. Make sure to visit your doctor..";
    }

} else {
    $diagnosis = "No matching conditions found. Make sure to visit your doctor..";
}

// ----------------------------
// 4) Insert into symptom_logs for analytics/charts
// ----------------------------

// Calculate pregnancy week and trimester
$userStmt = $conn->prepare("SELECT last_period FROM users WHERE id = ?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$res = $userStmt->get_result();
$userData = $res->fetch_assoc();
$userStmt->close();

$last_period = new DateTime($userData['last_period']);
$symptomDateObj = new DateTime($symptom_date);
$interval = $last_period->diff($symptomDateObj);
$weeks_pregnant = floor($interval->days / 7);

// Determine trimester
if ($weeks_pregnant <= 13) {
    $trimester = 1;

} elseif ($weeks_pregnant >= 14 && $weeks_pregnant <= 26) {
    $trimester = 2;

} elseif ($weeks_pregnant >= 27 && $weeks_pregnant <= 41) {
    $trimester = 3;

} else {
    $trimester = 3; // or "overdue", depending on what you want
}


// Insert each matched symptom into symptom_logs
$logStmt = $conn->prepare("
    INSERT INTO symptom_logs (user_id, symptom_name, severity_score, weighted_score, date_logged, week_number, trimester)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

foreach ($matched_ids as $symptom_id) {
    // Fetch severity/weight from symptom_condition or set default
    $sRes = $conn->query("SELECT weight FROM symptom_condition WHERE symptom_id = $symptom_id LIMIT 1");
    $sRow = $sRes->fetch_assoc();
    $weight = $sRow['weight'] ?? 0.1; // default weight 1.0

    $symptom_name = $symptoms[$symptom_id];
    $severity_score = 1.0; // or calculate based on user input
    $weighted_score = $severity_score * $weight;

$logStmt->bind_param("isddsii", $user_id, $symptom_name, $severity_score, $weighted_score, $symptom_date, $weeks_pregnant, $trimester);
    $logStmt->execute();
}

$logStmt->close();

    // ----------------------------
    // 3) Insert symptom + diagnosis into user_symptoms
    // ----------------------------
    $stmt = $conn->prepare("
    INSERT INTO user_symptoms (user_id, symptom_date, symptoms_text, diagnosis_html, created_at)
    VALUES (?, ?, ?, ?, NOW())
    ON DUPLICATE KEY UPDATE 
        symptoms_text = VALUES(symptoms_text),
        diagnosis_html = VALUES(diagnosis_html),
        created_at = NOW()
");


    if (!$stmt) throw new Exception("Prepare failed: ".$conn->error);
    $stmt->bind_param("isss", $user_id, $symptom_date, $input_text, $diagnosis);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        'status' => 'success',
        'message' => 'Symptom and diagnosis saved successfully',
        'diagnosis' => $diagnosis,
        'date' => $symptom_date,
        'symptom_text' => $input_text
    ]);

} catch (Exception $e) {
    echo json_encode(['status'=>'error','message'=>'Server error: '.$e->getMessage()]);
}
?>
