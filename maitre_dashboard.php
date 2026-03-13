<?php
session_start();
require_once '../includes/config.php';

// Déconnexion
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Vérification session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id_maitre = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

// Traitement de la validation du stage
if (isset($_POST['valider_stage'])) {
    $id_s = $_POST['id_stage_valider'];
    $prog = $_POST['progression_actuelle'];

    if ($prog >= 100) {
        $stmtV = $pdo->prepare("UPDATE stages SET etat_stage = 'Terminé', progression = 100 WHERE id_stage = ?");
        $stmtV->execute([$id_s]);
        $success_msg = "Le stage a été validé et clôturé avec succès !";
    }
}

// Mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $new_pass = $_POST['new_password'];

    try {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET nom_user = ?, prenom_user = ?, email = ? WHERE id_user = ?");
        $stmt->execute([$nom, $prenom, $email, $id_maitre]);

        if (!empty($new_pass)) {
            $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmtP = $pdo->prepare("UPDATE utilisateurs SET password = ? WHERE id_user = ?");
            $stmtP->execute([$hashed_pass, $id_maitre]);
        }

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $new_name = "avatar_" . $id_maitre . "." . $ext;
                $path = "../uploads/avatars/" . $new_name;
                if (!is_dir('../uploads/avatars/')) mkdir('../uploads/avatars/', 0777, true);
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $path)) {
                    $stmtImg = $pdo->prepare("UPDATE utilisateurs SET photo_url = ? WHERE id_user = ?");
                    $stmtImg->execute([$new_name, $id_maitre]);
                }
            }
        }
        $success_msg = "Profil mis à jour avec succès !";
    } catch (Exception $e) { $error_msg = "Erreur : " . $e->getMessage(); }
}

// Récupération infos encadreur
$stmtM = $pdo->prepare("SELECT nom_user, prenom_user, email, photo_url FROM utilisateurs WHERE id_user = ?");
$stmtM->execute([$id_maitre]);
$maitre = $stmtM->fetch();

$nom_maitre = strtoupper($maitre['nom_user']);
$photo_maitre = !empty($maitre['photo_url']) ? "../uploads/avatars/".$maitre['photo_url'] : ""; 
$page = isset($_GET['page']) ? $_GET['page'] : 'mes_stagiaires';

// Fonction de calcul de progression
function obtenirEtSauvegarderInfos($id_stage, $date_debut, $type_stage_texte, $pdo) {
    $debut = new DateTime($date_debut);
    $aujourdhui = new DateTime(); 
    preg_match('/\d+/', $type_stage_texte, $matches);
    $nb_mois = !empty($matches) ? (int)$matches[0] : 3; 
    $fin = clone $debut; $fin->modify("+$nb_mois months");
    
    $total_sec = $fin->getTimestamp() - $debut->getTimestamp();
    $ecoule_sec = $aujourdhui->getTimestamp() - $debut->getTimestamp();
    
    if ($aujourdhui < $debut) { $progression = 0; }
    elseif ($aujourdhui >= $fin) { $progression = 100; }
    else { $progression = ($ecoule_sec / $total_sec) * 100; }

    $progression_arrondie = round($progression);
    $update = $pdo->prepare("UPDATE stages SET progression = ? WHERE id_stage = ?");
    $update->execute([$progression_arrondie, $id_stage]);

    return ['progression' => $progression_arrondie, 'jours_restants' => ($aujourdhui > $fin) ? 0 : $aujourdhui->diff($fin)->days];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Encadreur | MFFE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --mffe-blue: #0d47a1; --mffe-orange: #ff9800; --sidebar-width: 280px; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; background: var(--mffe-blue); color: white; z-index: 1000; }
        .profile-area { padding: 35px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .avatar { width: 85px; height: 85px; border-radius: 50%; border: 3px solid var(--mffe-orange); margin: 0 auto 12px; background: white; overflow: hidden; }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }
        .nav-link { color: rgba(255,255,255,0.8); padding: 14px 25px; display: flex; align-items: center; text-decoration: none; transition: 0.3s; margin-left: 10px; border-radius: 30px 0 0 30px; }
        .nav-link.active { background: #f4f7f6; color: var(--mffe-blue); font-weight: 700; }

        /* HEADER */
        .top-header { margin-left: var(--sidebar-width); background: white; padding: 10px 40px; border-bottom: 4px solid var(--mffe-orange); display: flex; align-items: center; }
        
        /* LOGO MFFE */
        .header-logo { height: 50px; width: auto; }

        /* BANDEAU DÉFILANT */
        .marquee-container { flex-grow: 1; margin: 0 30px; overflow: hidden; background: #fffcf0; border-radius: 50px; padding: 6px 0; border: 1px solid #ffe0b2; }
        .marquee-text { display: inline-block; white-space: nowrap; font-weight: 800; color: var(--mffe-blue); text-transform: uppercase; font-size: 0.85rem; animation: scroll-x 25s linear infinite; padding-left: 100%; }
        @keyframes scroll-x { 0% { transform: translateX(0); } 100% { transform: translateX(-100%); } }

        .main-content { margin-left: var(--sidebar-width); padding: 40px; }
        .card-custom { border: none; border-radius: 18px; background: white; box-shadow: 0 8px 20px rgba(0,0,0,0.04); }
        .pulse-green { animation: pulse 1.5s infinite; background-color: #198754 !important; border: none; color: white; }
        @keyframes pulse { 0% { transform: scale(0.98); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7); } 70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); } 100% { transform: scale(0.98); } }
    </style>
</head>
<body>

    <nav class="sidebar">
        <div class="profile-area">
            <div class="avatar shadow-sm">
                <img src="<?= $photo_maitre ? $photo_maitre : '../img/default-avatar.png' ?>" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png'">
            </div>
            <h6 class="mb-1 fw-bold"><?= htmlspecialchars($maitre['prenom_user'] . ' ' . $nom_maitre) ?></h6>
            <span class="badge bg-warning text-dark small fw-bold">ENCADREUR</span>
        </div>
        <div class="mt-4">
            <a href="?page=mes_stagiaires" class="nav-link <?= ($page == 'mes_stagiaires' ? 'active' : '') ?>">
                <i class="bi bi-people-fill me-3"></i> Mes Stagiaires
            </a>
            <a href="?page=profil" class="nav-link <?= ($page == 'profil' ? 'active' : '') ?>">
                <i class="bi bi-person-gear me-3"></i> Mon Compte
            </a>
        </div>
        <div class="position-absolute bottom-0 w-100 p-4">
            <a href="?action=logout" class="btn btn-outline-light btn-sm w-100" onclick="return confirm('Déconnexion ?')">DÉCONNEXION</a>
        </div>
    </nav>

    <header class="top-header">
        <img src="../img/logo OFFICIEL MFFE_files/MIFFE.jpg" class="header-logo" alt="Logo MFFE">
        
        <div class="marquee-container d-none d-lg-block">
            <div class="marquee-text">
                Ministère de la Femme, de la Famille et de l'Enfant — Direction des Ressources Humaines (DRH) — GESTA-WEB 2026
            </div>
        </div>

        <div class="ms-auto text-end text-muted small fw-bold">
            <i class="bi bi-calendar3 me-2"></i><?= date('d/m/Y') ?>
        </div>
    </header>

    <main class="main-content">
        <?php if($success_msg): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= $success_msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($page == 'mes_stagiaires'): ?>
            <h3 class="fw-bold mb-4" style="color: var(--mffe-blue);">SUIVI DE L'ENCADREMENT</h3>
            <div class="row">
                <?php
                $stmtS = $pdo->prepare("SELECT s.*, d.nom_etudiant, d.prenom_etudiant FROM stages s JOIN demandes d ON s.id_demande = d.id_demande WHERE s.id_maitre = ? AND s.etat_stage = 'En cours'");
                $stmtS->execute([$id_maitre]);
                $stagiaires = $stmtS->fetchAll();
                
                foreach ($stagiaires as $s): 
                    $infos = obtenirEtSauvegarderInfos($s['id_stage'], $s['date_debut'], $s['type_stage'], $pdo);
                ?>
                <div class="col-12 mb-3">
                    <div class="card card-custom p-4">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <small class="text-muted fw-bold">NOM & PRÉNOMS</small>
                                <h5 class="fw-bold mb-0 text-primary"><?= strtoupper($s['nom_etudiant']) ?></h5>
                                <p class="mb-0 text-muted small"><?= $s['prenom_etudiant'] ?></p>
                            </div>
                            <div class="col-md-5">
                                <div class="d-flex justify-content-between mb-1 small fw-bold"><span>Progression</span><span><?= $infos['progression'] ?>%</span></div>
                                <div class="progress" style="height: 12px; border-radius: 10px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated <?= ($infos['progression'] >= 100) ? 'bg-success' : 'bg-primary' ?>" style="width: <?= $infos['progression'] ?>%"></div>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="bg-light p-2 rounded border">
                                    <h4 class="fw-bold mb-0"><?= $infos['jours_restants'] ?></h4>
                                    <small class="text-muted fw-bold" style="font-size: 0.6rem;">JOURS RESTANTS</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <form method="POST" onsubmit="return checkProg(<?= $infos['progression'] ?>)">
                                    <input type="hidden" name="id_stage_valider" value="<?= $s['id_stage'] ?>">
                                    <input type="hidden" name="progression_actuelle" value="<?= $infos['progression'] ?>">
                                    <button type="submit" name="valider_stage" class="btn btn-sm w-100 rounded-pill fw-bold <?= ($infos['progression'] >= 100) ? 'pulse-green' : 'btn-outline-secondary' ?>">VALIDER</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($page == 'profil'): ?>
            <div class="card card-custom p-4">
                <h4 class="fw-bold mb-4 text-primary"><i class="bi bi-person-gear me-2"></i>Mon Compte Professionnel</h4>
                <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validatePass()">
                    <div class="row">
                        <div class="col-md-3 text-center border-end">
                            <div class="avatar mx-auto mb-3" style="width: 120px; height: 120px;">
                                <img src="<?= $photo_maitre ? $photo_maitre : 'https://via.placeholder.com/120' ?>" id="preview">
                            </div>
                            <label class="btn btn-sm btn-outline-primary">Modifier Photo <input type="file" name="photo" hidden onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])"></label>
                        </div>
                        <div class="col-md-9 px-4">
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label small fw-bold">Nom</label><input type="text" name="nom" class="form-control" value="<?= $maitre['nom_user'] ?>" required></div>
                                <div class="col-md-6"><label class="form-label small fw-bold">Prénom</label><input type="text" name="prenom" class="form-control" value="<?= $maitre['prenom_user'] ?>" required></div>
                                <div class="col-12"><label class="form-label small fw-bold">Email</label><input type="email" name="email" class="form-control" value="<?= $maitre['email'] ?>" required></div>
                                <div class="col-md-6"><label class="form-label small fw-bold text-danger">Nouveau mot de passe</label><input type="password" name="new_password" id="p1" class="form-control"></div>
                                <div class="col-md-6"><label class="form-label small fw-bold text-danger">Confirmer mot de passe</label><input type="password" id="p2" class="form-control"></div>
                                <div class="col-12 mt-4"><button type="submit" name="update_profile" class="btn btn-primary px-5 rounded-pill fw-bold">SAUVEGARDER</button></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function checkProg(p) {
            if (p < 100) { alert("Le stage est encore à " + p + "%. La validation n'est possible qu'à 100%."); return false; }
            return confirm("Confirmer la validation finale ?");
        }
        function validatePass() {
            const v1 = document.getElementById('p1').value;
            const v2 = document.getElementById('p2').value;
            if (v1 !== "" && v1 !== v2) { alert("Les mots de passe ne correspondent pas."); return false; }
            return true;
        }
    </script>
</body>
</html>