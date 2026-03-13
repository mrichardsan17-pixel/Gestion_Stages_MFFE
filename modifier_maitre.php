<?php
require_once '../includes/config.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php?page=maitres");
    exit();
}

$id = $_GET['id'];


$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_user = ?");
$stmt->execute([$id]);
$maitre = $stmt->fetch();


$directions = $pdo->query("SELECT * FROM directions ORDER BY nom_direction ASC")->fetchAll();

if (!$maitre) {
    die("Maître de stage introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <title>Modifier Maître - DRH</title>
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-edit { border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); overflow: hidden; }
        .header-edit { background: #0d47a1; color: white; padding: 25px; }
        .form-label { color: #495057; }
        .btn-primary { background-color: #0d47a1; border: none; }
        .btn-primary:hover { background-color: #0a3a85; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <a href="dashboard.php?page=maitres" class="btn btn-link text-decoration-none text-muted mb-3 p-0">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>

            <div class="card card-edit">
                <div class="header-edit text-center">
                    <i class="bi bi-person-gear fs-1 mb-2"></i>
                    <h4 class="fw-bold m-0">Profil de l'Encadreur</h4>
                </div>
                <div class="card-body p-4 p-lg-5">
                    <form action="../includes/update_maitre.php" method="POST">
                        <input type="hidden" name="id_user" value="<?= $maitre['id_user'] ?>">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Prénom</label>
                                <input type="text" name="prenom" class="form-control form-control-lg" value="<?= htmlspecialchars($maitre['prenom_user']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Nom</label>
                                <input type="text" name="nom" class="form-control form-control-lg" value="<?= htmlspecialchars($maitre['nom_user']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-uppercase">Email (Identifiant)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope-at"></i></span>
                                <input type="email" name="email" class="form-control form-control-lg bg-light" value="<?= htmlspecialchars($maitre['email']) ?>" readonly>
                            </div>
                            <div class="form-text text-danger italic"><i class="bi bi-info-circle"></i> L'identifiant de connexion est fixe.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase">Direction d'Affectation</label>
                            <select name="id_direction" class="form-select form-select-lg" required>
                                <option value="">Choisir une direction...</option>
                                <?php foreach($directions as $dir): ?>
                                    <option value="<?= $dir['id_direction'] ?>" <?= ($dir['id_direction'] == ($maitre['id_direction'] ?? '')) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dir['nom_direction']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="bi bi-save me-2"></i>Enregistrer les modifications
                            </button>
                            <a href="dashboard.php?page=maitres" class="btn btn-outline-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>