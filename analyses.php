<?php
if (!isset($pdo)) {
    require_once '../includes/config.php';
}

try {
    // 1. STATS PAR GENRE : Uniquement ceux qui ont effectué un stage (INNER JOIN)
    $stmtGenre = $pdo->query("SELECT d.genre, COUNT(s.id_stage) as nb 
                              FROM demandes d 
                              INNER JOIN stages s ON d.id_demande = s.id_demande 
                              GROUP BY d.genre");
    $statsGenre = $stmtGenre->fetchAll(PDO::FETCH_ASSOC);

    // 2. STATS PAR DIRECTION
    $stmtDir = $pdo->query("SELECT dir.nom_direction, COUNT(s.id_stage) as nb 
                            FROM directions dir 
                            LEFT JOIN stages s ON dir.id_direction = s.id_direction 
                            GROUP BY dir.nom_direction");
    $statsDir = $stmtDir->fetchAll(PDO::FETCH_ASSOC);

    // 3. STATS PAR ANNÉE
    $stmtAnnee = $pdo->query("SELECT YEAR(date_debut) as annee, COUNT(*) as nb 
                              FROM stages 
                              GROUP BY annee 
                              ORDER BY annee DESC");
    $statsAnnee = $stmtAnnee->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur stats : " . $e->getMessage());
}

// Préparation des données pour JavaScript
$labelsGenre = []; $dataGenre = [];
foreach($statsGenre as $g) {
    $labelsGenre[] = ($g['genre'] == 'M') ? 'Hommes' : 'Femmes';
    $dataGenre[] = $g['nb'];
}

$labelsDir = []; $dataDir = [];
foreach($statsDir as $d) {
    $labelsDir[] = $d['nom_direction'];
    $dataDir[] = $d['nb'];
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Analyses Statistiques des Stages</h3>
        <button class="btn btn-sm btn-outline-primary" onclick="window.print()"><i class="bi bi-printer me-2"></i>Exporter</button>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 p-4 h-100">
                <h6 class="fw-bold text-muted mb-4 text-uppercase small text-center"><i class="bi bi-pie-chart-fill me-2"></i>Répartition par Genre</h6>
                <div style="height: 250px;">
                    <canvas id="chartGenre"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4 h-100">
                <h6 class="fw-bold text-muted mb-4 text-uppercase small"><i class="bi bi-bar-chart-fill me-2"></i>Évolution des effectifs par Session</h6>
                <div style="height: 250px;">
                    <canvas id="chartAnnee"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-0 p-4">
                <h6 class="fw-bold text-muted mb-4 text-uppercase small"><i class="bi bi-building-fill me-2"></i>Répartition par Direction</h6>
                <div style="height: 400px;">
                    <canvas id="chartDir"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Couleurs MFFE
const mffeOrange = '#FF8200';
const mffeBlue = '#003366';
const mffeGreen = '#00663e';

// Configuration Graphique Genre
new Chart(document.getElementById('chartGenre'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($labelsGenre) ?>,
        datasets: [{
            data: <?= json_encode($dataGenre) ?>,
            backgroundColor: [mffeBlue, '#dc3545'],
            borderWidth: 2
        }]
    },
    options: { maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
});

// Configuration Graphique Année
new Chart(document.getElementById('chartAnnee'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($statsAnnee, 'annee')) ?>,
        datasets: [{
            label: 'Nombre de stagiaires',
            data: <?= json_encode(array_column($statsAnnee, 'nb')) ?>,
            backgroundColor: mffeOrange
        }]
    },
    options: { maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

// Configuration Graphique Directions
new Chart(document.getElementById('chartDir'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labelsDir) ?>,
        datasets: [{
            label: 'Stagiaires affectés',
            data: <?= json_encode($dataDir) ?>,
            backgroundColor: mffeGreen
        }]
    },
    options: {
        indexAxis: 'y',
        maintainAspectRatio: false,
        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } },
        plugins: { legend: { display: false } }
    }
});
</script>