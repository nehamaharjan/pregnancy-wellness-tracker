<?php
session_start();
include '../includes/db.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../auth/login.php");
    exit;
}

// Total symptom logs
$totalLogsQuery = $conn->query("SELECT COUNT(*) AS total FROM user_symptoms");
$totalLogs = $totalLogsQuery->fetch_assoc()['total'];

// --- New Logic: Split and Count Individual Symptoms ---
$query = "SELECT symptoms_text FROM user_symptoms WHERE symptoms_text IS NOT NULL AND symptoms_text != ''";
$result = $conn->query($query);

$symptomCounts = [];

while($row = $result->fetch_assoc()){
    // Split each record by comma
    $symptoms = explode(',', $row['symptoms_text']);
    foreach($symptoms as $symptom){
        $symptom = trim(strtolower($symptom)); // Clean spacing + lowercase
        if($symptom !== ''){
            if(!isset($symptomCounts[$symptom])){
                $symptomCounts[$symptom] = 0;
            }
            $symptomCounts[$symptom]++;
        }
    }
}

// Sort by highest count
arsort($symptomCounts);

// Take top 10
$topSymptoms = array_keys(array_slice($symptomCounts, 0, 10, true));
$topCounts   = array_values(array_slice($symptomCounts, 0, 10, true));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Symptom Statistics - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php include 'include/slidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Symptom Statistics</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Symptom Overview</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Total Symptoms Logged:</strong> <?php echo $totalLogs; ?></p>

                        <h4>Top 10 Reported Symptoms</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Symptom</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php 
                                    // Take only top 10 for table
                                    $top10Counts = array_slice($symptomCounts, 0, 10, true);
                                    foreach($top10Counts as $symptom => $count): 
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(ucfirst($symptom)); ?></td>
                                        <td><?php echo $count; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                        </table>

                        <canvas id="symptomChart" height="100"></canvas>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('symptomChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($topSymptoms); ?>,
        datasets: [{
            label: 'Most Reported Symptoms',
            data: <?php echo json_encode($topCounts); ?>,
            backgroundColor: 'rgba(239, 133, 227, 0.6)',
            borderColor: 'rgba(255, 0, 225, 0.6)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
