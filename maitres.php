<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/config.php';


if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'ADMIN' && $_SESSION['role'] !== 'DRH')) {
    
    
}

$message = "";


if (isset($_POST['inscrire_maitre'])) {
    $nom = strtoupper(htmlspecialchars($_POST['nom']));
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $id_direction = !empty($_POST['direction']) ? intval($_POST['direction']) : null; 
    
    
    $mdp_hache = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    
    $check = $pdo->prepare("SELECT id_user FROM utilisateurs WHERE email = ?");
    $check->execute([$email]);
    
    if ($check->rowCount() > 0) {
        $message = "<div class='alert alert-danger shadow-sm'><i class='bi bi-exclamation-triangle me-2'></i>Cet email est déjà utilisé.</div>";
    } else {
        try {
            $sql = "INSERT INTO utilisateurs (nom_user, prenom_user, email, id_direction, mot_de_passe, role) 
                    VALUES (?, ?, ?, ?, ?, 'MAITRE')";
            $ins = $pdo->prepare($sql);
            
            if ($ins->execute([$nom, $prenom, $email, $id_direction, $mdp_hache])) {
                $message = "<div class='alert alert-success shadow-sm'><i class='bi bi-check-circle me-2'></i>Maître de stage inscrit avec succès !</div>";
            }
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>Erreur technique : " . $e->getMessage() . "</div>";
        }
    }
}


if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    
    $check_stages = $pdo->prepare("SELECT COUNT(*) FROM stages WHERE id_maitre = ?");
    $check_stages->execute([$id]);
    
    if ($check_stages->fetchColumn() > 0) {
        $message = "<div class='alert alert-warning shadow-sm'>Impossible de supprimer : ce maître est lié à des stages en cours ou archivés.</div>";
    } else {
        $del = $pdo->prepare("DELETE FROM utilisateurs WHERE id_user = ? AND role = 'MAITRE'");
        if ($del->execute([$id])) {
            
            echo "<script>window.location.href='dashboard.php?page=maitres&msg=supprime';</script>";
            exit();
        }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] == 'supprime') {
    $message = "<div class='alert alert-info shadow-sm'><i class='bi bi-info-circle me-2'></i>L'encadreur a été supprimé avec succès.</div>";
}


$liste_directions = $pdo->query("SELECT * FROM directions ORDER BY nom_direction ASC")->fetchAll();

$stmt = $pdo->prepare("SELECT u.*, d.nom_direction 
                       FROM utilisateurs u 
                       LEFT JOIN directions d ON u.id_direction = d.id_direction 
                       WHERE u.role = 'MAITRE' 
                       ORDER BY u.nom_user ASC");
$stmt->execute();
$maitres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --drh-blue: #003366; --drh-orange: #FF8200; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', sans-serif; }
        .page-header { background: white; padding: 25px; border-bottom: 5px solid var(--drh-orange); box-shadow: 0 2px 15px rgba(0,0,0,0.08); margin-bottom: 30px; }
        .card-custom { border-radius: 15px; border: none; box-shadow: 0 8px 25px rgba(0,0,0,0.05); }
        .table thead { background-color: var(--drh-blue); color: white; }
        .avatar-circle { width: 40px; height: 40px; background: var(--drh-blue); color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; }
    </style>
</head>
<body>

<div class="container-fluid px-0">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="ps-4">
            <h1 class="fw-bold text-dark mb-0"><i class="bi bi-person-badge-fill text-primary me-2"></i>GESTION DES MAÎTRES</h1>
        </div>
        <div class="pe-4">
            <button class="btn btn-primary shadow-sm fw-bold px-4 rounded-pill" data-bs-toggle="collapse" data-bs-target="#formInscrire">
                <i class="bi bi-plus-lg me-2"></i>NOUVEL ENCADREUR
            </button>
        </div>
    </div>

    <div class="container pb-5">
        <?= $message ?>

        <div class="collapse mb-5" id="formInscrire">
            <div class="card card-custom p-4 border-top border-5 border-primary shadow">
                <form method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nom de famille</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Prénoms</label>
                        <input type="text" name="prenom" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email Professionnel</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Direction d'affectation</label>
                        <select name="direction" class="form-select" required>
                            <option value="">-- Choisir une direction --</option>
                            <?php foreach($liste_directions as $d): ?>
                                <option value="<?= $d['id_direction'] ?>"><?= htmlspecialchars($d['nom_direction']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Mot de passe temporaire</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" name="inscrire_maitre" class="btn btn-success fw-bold px-5 rounded-pill shadow">
                            <i class="bi bi-save me-2"></i>ENREGISTRER
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-custom overflow-hidden shadow">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="ps-4">IDENTITÉ</th>
                            <th>EMAIL</th>
                            <th>DIRECTION</th>
                            <th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($maitres as $m): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center py-2">
                                    <div class="avatar-circle me-3"><?= substr($m['nom_user'], 0, 1) ?></div>
                                    <div>
                                        <div class="fw-bold"><?= htmlspecialchars($m['nom_user']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($m['prenom_user']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($m['email']) ?></td>
                            <td><span class="badge bg-light text-dark border px-3"><?= htmlspecialchars($m['nom_direction'] ?? 'N/A') ?></span></td>
                            <td class="text-center">
                                <a href="dashboard.php?page=maitres&delete=<?= $m['id_user'] ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Attention : Êtes-vous sûr de vouloir supprimer ce maître ?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($maitres)): ?>
                            <tr><td colspan="4" class="text-center p-4">Aucun encadreur trouvé.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>