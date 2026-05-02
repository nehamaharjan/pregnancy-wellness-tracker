<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}
$user_id = (int)$_SESSION['user_id'];

// selected trimester filter (for pie)
$selected_trimester = isset($_GET['trimester']) ? intval($_GET['trimester']) : 1;

// ------------------ 1) Symptom Frequency (pie) ------------------
$freqStmt = $conn->prepare("
    SELECT symptom_name, COUNT(*) AS total
    FROM symptom_logs
    WHERE user_id = ? AND trimester = ?
    GROUP BY symptom_name
    ORDER BY total DESC
    LIMIT 12
");
$freqStmt->bind_param("ii", $user_id, $selected_trimester);
$freqStmt->execute();
$symptom_freq = $freqStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$freqStmt->close();

// ------------------ 2) Daily Severity Trend (line) for all trimesters ------------------
$dailyStmt = $conn->prepare("
    SELECT trimester, date_logged, SUM(weighted_score) AS total_weight
    FROM symptom_logs
    WHERE user_id = ?
    GROUP BY trimester, date_logged
    ORDER BY date_logged ASC
");
$dailyStmt->bind_param("i", $user_id);
$dailyStmt->execute();
$daily_rows = $dailyStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$dailyStmt->close();

// organize daily severity per trimester
$daily_data = [];
$all_dates = [];
foreach ($daily_rows as $row) {
    $t = (int)$row['trimester'];
    $date = $row['date_logged'];
    if (!in_array($date, $all_dates)) $all_dates[] = $date;
    if (!isset($daily_data[$t])) $daily_data[$t] = [];
    $daily_data[$t][$date] = round((float)$row['total_weight'], 2);
}
sort($all_dates);

// ------------------ 3) Trimester Comparison (bar) ------------------
$trimCmpStmt = $conn->prepare("
    SELECT trimester, SUM(weighted_score) AS total_weight
    FROM symptom_logs
    WHERE user_id = ?
    GROUP BY trimester
    ORDER BY trimester
");
$trimCmpStmt->bind_param("i", $user_id);
$trimCmpStmt->execute();
$trimster_rows = $trimCmpStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$trimCmpStmt->close();

// final JS data
$js = [
    'pie_labels' => array_column($symptom_freq, 'symptom_name'),
    'pie_values' => array_map('intval', array_column($symptom_freq, 'total')),
    'daily_dates' => $all_dates,
    'daily_data' => $daily_data,
    'trim_labels' => ['Trimester 1','Trimester 2','Trimester 3'],
    'trim_values' => array_column($trimster_rows,'total_weight'),
    'selected_trimester' => $selected_trimester
];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Pregnancy Symptom Dashboard</title>
<?php include('../includes/header.php'); ?>
<link rel="stylesheet" href="../assets/css/discussion.css" />
<link rel="stylesheet" href="../assets/css/dashboard.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
.container { max-width:1200px; margin:20px auto; padding:10px; }
.top-bar { text-align:center; margin-bottom:15px; }
.charts-row { display:flex; flex-wrap:wrap; gap:10px; justify-content:center; }
.chart-card { background:#fff; border-radius:10px; padding:15px; box-shadow:0 6px 18px rgba(20,20,20,0.05); }
.chart-card.small { width:45%; min-width:450px; }
.chart-card.full { width:44%; min-width:400px; }

@media(max-width:900px){ .chart-card.small, .chart-card.full { width:100%; } }
</style>
</head>
<body>
<div class=discussion-header>
    <h2>Pregnancy Symptom Dashboard</h2></div>
    <hr>
<div class="container">
    <div class="top-bar">
        
        <div>
            <select id="trimester" onchange="changeTrimester(this.value)">
                <option value="1" <?= $selected_trimester===1?'selected':'' ?>>Trimester 1</option>
                <option value="2" <?= $selected_trimester===2?'selected':'' ?>>Trimester 2</option>
                <option value="3" <?= $selected_trimester===3?'selected':'' ?>>Trimester 3</option>
            </select>
        </div>
    </div>

    <!-- Pie chart top -->
    <div class="charts-row">
        <div class="chart-card full">
            <h2>Most Frequent Symptoms — Trimester <?= $selected_trimester ?></h2>
            <canvas id="pieChart"></canvas>
        </div>
    </div>
    <hr>

    <!-- Line and Bar side by side -->
    <div class="charts-row">
        <div class="chart-card small">
            <h2>Good days & Bad days </h2>
            <canvas id="lineChart"></canvas>
        </div>
        <div class="chart-card small">
            <h2>Total Severity by Trimester</h2>
            <canvas id="barChart"></canvas>
        </div>
    </div>
</div>

<script>
const data = <?= json_encode($js) ?>;

// ---------- PIE ----------
const pieCtx = document.getElementById('pieChart').getContext('2d');
const pieColors = data.pie_labels.map((_,i)=> `hsl(${(i*40)%360} 70% 55%)`);
new Chart(pieCtx,{
    type:'pie',
    data:{ labels:data.pie_labels, datasets:[{ data:data.pie_values, backgroundColor:pieColors }] },
    options:{ responsive:true, plugins:{ legend:{ position:'right' } } }
});

// ---------- LINE ----------
const lineCtx = document.getElementById('lineChart').getContext('2d');
const lineColors = ['rgba(54,162,235,1)','rgba(40,167,69,1)','rgba(255,193,7,1)'];
const lineDatasets = [1,2,3].map(t=>{
    const values = data.daily_dates.map(d => data.daily_data[t] && data.daily_data[t][d] ? data.daily_data[t][d] : null);
    return { label:'Trimester '+t, data:values, borderColor:lineColors[t-1], fill:false, tension:0.3, spanGaps:true, pointRadius:3 };
});
new Chart(lineCtx,{
    type:'line',
    data:{ labels:data.daily_dates, datasets:lineDatasets },
    options:{ responsive:true, scales:{ y:{ beginAtZero:true, max:30 }, x:{ ticks:{ maxRotation:45,minRotation:0 } } } }
});

// ---------- BAR ----------
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx,{
    type:'bar',
    data:{ labels:data.trim_labels, datasets:[{ label:'Total Severity', data:data.trim_values, backgroundColor:['rgba(0,123,255,0.7)','rgba(40,167,69,0.7)','rgba(255,193,7,0.7)'] }] },
    options:{ responsive:true, scales:{ y:{ beginAtZero:true, max:30 } } }
});

// ---------- trimester change ----------
function changeTrimester(t){ window.location.href='?trimester='+encodeURIComponent(t); }
</script>
</body>
</html>
