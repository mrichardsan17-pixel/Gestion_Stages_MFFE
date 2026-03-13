<?php 
ob_start();
session_start();

if (file_exists('../includes/config.php')) {
    require_once '../includes/config.php'; 
} else {
    die("Erreur critique : Le fichier de configuration (config.php) est introuvable.");
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); 
    exit();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'stats';

try {
    $total = $pdo->query("SELECT COUNT(*) FROM demandes")->fetchColumn() ?: 0;
    $attente = $pdo->query("SELECT COUNT(*) FROM demandes WHERE statut_demande = 'En attente'")->fetchColumn() ?: 0;
    $acceptes = $pdo->query("SELECT COUNT(*) FROM stages")->fetchColumn() ?: 0;
    $rejetes = $pdo->query("SELECT COUNT(*) FROM demandes WHERE statut_demande = 'Refusé'")->fetchColumn() ?: 0;
    $en_cours = $pdo->query("SELECT COUNT(*) FROM stages WHERE etat_stage = 'En cours'")->fetchColumn() ?: 0;
    $termines = $pdo->query("SELECT COUNT(*) FROM stages WHERE etat_stage = 'Terminé'")->fetchColumn() ?: 0;
} catch (Exception $e) {
    $total = $attente = $acceptes = $rejetes = $en_cours = $termines = 0;
}

$titres_pages = [
    'stats'        => 'Vue d\'ensemble',
    'analyses'     => 'Analyses Statistiques',
    'candidatures' => 'Gestion des Candidatures',
    'affectation'  => 'Nouvelles Affectations',
    'stages_cours' => 'Suivi des Stages Actifs',
    'stages_fin'   => 'Archives des Stages',
    'directions'   => 'Gestion des Directions',
    'maitres'      => 'Répertoire des Encadreurs'
];
$titre_actuel = $titres_pages[$page] ?? 'Administration';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre_actuel ?> | MFFE Gestion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { 
            --mffe-blue: #003366; 
            --mffe-orange: #FF8200; 
            --mffe-green-dark: #00663e; 
            --sidebar-bg: #2c3e50; 
            --sidebar-width: 280px;
        }
        body { background-color: #f4f7f6; display: flex; height: 100vh; overflow: hidden; font-family: 'Segoe UI', sans-serif; }
        .sidebar { width: var(--sidebar-width); background: var(--sidebar-bg); color: white; display: flex; flex-direction: column; flex-shrink: 0; }
        .admin-profile { padding: 30px 20px; text-align: center; background: rgba(0,0,0,0.2); }
        .admin-avatar { width: 70px; height: 70px; border-radius: 50%; border: 3px solid var(--mffe-orange); margin-bottom: 10px; background: white; object-fit: cover; }
        .nav-link { color: rgba(255, 255, 255, 0.7); padding: 11px 25px; transition: 0.3s; border-left: 4px solid transparent; text-decoration: none; display: block; font-size: 0.95rem; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.1); color: white; border-left-color: var(--mffe-orange); }
        .nav-link i { margin-right: 15px; width: 20px; text-align: center; }
        .main-wrapper { flex-grow: 1; display: flex; flex-direction: column; min-width: 0; }
        header { background: white; padding: 10px 30px; border-bottom: 3px solid var(--mffe-orange); display: flex; align-items: center; flex-shrink: 0; }
        .logo-mffe { height: 50px; }
        .marquee-container { flex-grow: 1; margin: 0 20px; overflow: hidden; white-space: nowrap; background: #f8f9fa; border-radius: 5px; padding: 5px 0; }
        .marquee-text { display: inline-block; font-weight: 700; color: var(--mffe-green-dark); text-transform: uppercase; padding-left: 100%; animation: scroll-header 25s linear infinite; }
        @keyframes scroll-header { 0% { transform: translateX(0); } 100% { transform: translateX(-100%); } }
        .content-area { padding: 30px; overflow-y: auto; flex-grow: 1; background: #f8fafb; }
        .main-interface-title { font-size: 1.8rem; font-weight: 800; color: var(--mffe-blue); margin-bottom: 30px; text-transform: uppercase; }
        .card-custom { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: none; border-left: 6px solid var(--mffe-orange); height: 100%; }
        .border-total { border-left-color: var(--mffe-blue); }
        .border-attente { border-left-color: #ffc107; }
        .border-accepte { border-left-color: #0dcaf0; }
        .border-rejete { border-left-color: #dc3545; }
        .border-cours { border-left-color: #fd7e14; }
        .border-termine { border-left-color: var(--mffe-green-dark); }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="admin-profile">
            <img src="../Img/avatar_admin.png" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png'" class="admin-avatar">
            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($_SESSION['nom_user'] ?? 'Administrateur'); ?></h6>
            <p class="mb-0 small text-uppercase" style="color: var(--mffe-orange); font-size: 0.7rem;">Gestionnaire DRH</p>
        </div>
        <nav class="flex-grow-1 py-3 overflow-y-auto">
            <a href="dashboard.php?page=stats" class="nav-link <?= $page=='stats'?'active':'' ?>"><i class="bi bi-speedometer2"></i> Vue globale</a>
            <a href="dashboard.php?page=candidatures" class="nav-link <?= $page=='candidatures'?'active':'' ?>"><i class="bi bi-envelope-paper"></i> Candidatures</a>
            <a href="dashboard.php?page=affectation" class="nav-link <?= $page=='affectation'?'active':'' ?>"><i class="bi bi-person-plus"></i> Affectations</a>
            <div class="px-4 py-2 mt-2 small text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Suivi & Analyses</div>
            <a href="dashboard.php?page=stages_cours" class="nav-link <?= $page=='stages_cours'?'active':'' ?>"><i class="bi bi-play-circle"></i> Stages actifs</a>
            <a href="dashboard.php?page=stages_fin" class="nav-link <?= $page=='stages_fin'?'active':'' ?>"><i class="bi bi-archive"></i> Archives</a>
            <a href="dashboard.php?page=analyses" class="nav-link <?= $page=='analyses'?'active':'' ?>"><i class="bi bi-bar-chart-line"></i> Statistiques</a>
            <div class="px-4 py-2 mt-2 small text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">Configuration</div>
            <a href="dashboard.php?page=directions" class="nav-link <?= $page=='directions'?'active':'' ?>"><i class="bi bi-building"></i> Directions</a>
            <a href="dashboard.php?page=maitres" class="nav-link <?= $page=='maitres'?'active':'' ?>"><i class="bi bi-person-badge"></i> Maîtres de Stage</a>
        </nav>
        <div class="p-3 border-top border-secondary">
            <a href="deconnexion.php" class="btn btn-danger btn-sm w-100 fw-bold shadow-sm" onclick="return confirm('Souhaitez-vous vous déconnecter ?')">
                <i class="bi bi-power me-2"></i>DÉCONNEXION
            </a>
        </div>
    </div>

    <div class="main-wrapper">
        <header>
            <img src="../img/logo OFFICIEL MFFE_files/MIFFE.jpg" class="logo-mffe" alt="Logo MFFE">
            <div class="marquee-container d-none d-md-block">
                <div class="marquee-text">Ministère de la Femme, de la Famille et de l'Enfant — DRH — GESTA-WEB 2026</div>
            </div>
            <div class="text-end ms-auto">
                <div class="small fw-bold text-muted">Aujourd'hui</div>
                <div class="small fw-bold" style="color: var(--mffe-blue);"><?= date('d/m/Y') ?></div>
            </div>
        </header>

        <main class="content-area">
            <h1 class="main-interface-title"><?= $titre_actuel ?></h1>
            <?php if($page == 'stats'): ?>
                <div class="row g-4">
                    <div class="col-md-4"><div class="card-custom border-total"><h6>TOTAL DEMANDES</h6><h2><?= $total ?></h2></div></div>
                    <div class="col-md-4"><div class="card-custom border-attente"><h6>EN ATTENTE</h6><h2 class="text-warning"><?= $attente ?></h2></div></div>
                    <div class="col-md-4"><div class="card-custom border-rejete"><h6>REJETÉS</h6><h2 class="text-danger"><?= $rejetes ?></h2></div></div>
                    <div class="col-md-4"><div class="card-custom border-accepte"><h6>ACCEPTÉS</h6><h2 class="text-info"><?= $acceptes ?></h2></div></div>
                    <div class="col-md-4"><div class="card-custom border-cours"><h6>EN COURS</h6><h2 style="color: #fd7e14;"><?= $en_cours ?></h2></div></div>
                    <div class="col-md-4"><div class="card-custom border-termine"><h6>ARCHIVES</h6><h2 class="text-success"><?= $termines ?></h2></div></div>
                </div>
            <?php else: 
                $file = [
                    'candidatures' => 'liste_demandes.php',
                    'affectation'  => 'nouvelle_affectation.php',
                    'stages_cours' => 'stages_actifs.php',
                    'stages_fin'   => 'stages_termines.php',
                    'analyses'     => 'analyses.php',
                    'directions'   => 'gestion_directions.php',
                    'maitres'      => 'maitres.php'
                ][$page] ?? null;
                if($file && file_exists($file)) include $file; else echo "Page en construction...";
            endif; ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>