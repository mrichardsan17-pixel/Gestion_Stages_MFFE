<?php



$message_notif = "";


if (isset($_GET['delete_dir'])) {
    $id_del = intval($_GET['delete_dir']);
    
    
    $check_usage = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE id_direction = ?");
    $check_usage->execute([$id_del]);
    
    if ($check_usage->fetchColumn() > 0) {
        $message_notif = "<div class='alert alert-danger border-0 shadow-sm'><i class='bi bi-x-circle me-2'></i>Suppression impossible : des utilisateurs sont rattachés à cette direction.</div>";
    } else {
        $delete = $pdo->prepare("DELETE FROM directions WHERE id_direction = ?");
        if ($delete->execute([$id_del])) {
            echo "<script>window.location.href='dashboard.php?page=directions&msg=deleted';</script>";
            exit();
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_direction'])) {
    $nom_direction = trim(htmlspecialchars($_POST['nom_direction']));
    
    if (!empty($nom_direction)) {
        $check = $pdo->prepare("SELECT id_direction FROM directions WHERE LOWER(nom_direction) = LOWER(?)");
        $check->execute([$nom_direction]);
        
        if ($check->rowCount() > 0) {
            $message_notif = "<div class='alert alert-warning border-0 shadow-sm'>Cette direction existe déjà.</div>";
        } else {
            $insert = $pdo->prepare("INSERT INTO directions (nom_direction) VALUES (?)");
            if ($insert->execute([$nom_direction])) {
                $message_notif = "<div class='alert alert-success border-0 shadow-sm'>Direction ajoutée avec succès !</div>";
            }
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editer_direction'])) {
    $id_dir = intval($_POST['id_direction']);
    $nouveau_nom = trim(htmlspecialchars($_POST['nom_direction']));

    if (!empty($nouveau_nom)) {
        $update = $pdo->prepare("UPDATE directions SET nom_direction = ? WHERE id_direction = ?");
        if ($update->execute([$nouveau_nom, $id_dir])) {
            $message_notif = "<div class='alert alert-success border-0 shadow-sm'><i class='bi bi-check-circle me-2'></i>Direction mise à jour avec succès !</div>";
        }
    }
}


if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
    $message_notif = "<div class='alert alert-info border-0 shadow-sm'>La direction a été supprimée.</div>";
}


$sql = "SELECT d.id_direction, d.nom_direction, 
        (SELECT COUNT(*) FROM stages s WHERE s.id_direction = d.id_direction AND s.etat_stage = 'En cours') as nb_stagiaires
        FROM directions d 
        ORDER BY d.nom_direction ASC";
$directions = $pdo->query($sql)->fetchAll();
?>

<div class="container-fluid p-0">
    <?= $message_notif ?>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 15px;">
                <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-plus-circle-fill me-2"></i>Nouvelle Direction</h5>
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">NOM DE LA DIRECTION</label>
                        <input type="text" name="nom_direction" class="form-control border-primary py-2" placeholder="Ex: Direction Générale" required>
                    </div>
                    <button type="submit" name="ajouter_direction" class="btn btn-primary w-100 fw-bold py-2">
                        <i class="bi bi-save me-2"></i>ENREGISTRER
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm bg-white" style="border-radius: 15px; overflow: hidden;">
                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0"><i class="bi bi-building-fill me-2"></i>Liste des Directions</h5>
                    <span class="badge bg-dark"><?= count($directions) ?> Total</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>NOM DE LA DIRECTION</th>
                                <th class="text-center">STAGIAIRES</th>
                                <th class="text-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($directions as $dir): ?>
                            <tr>
                                <td class="ps-4 text-muted">#<?= $dir['id_direction'] ?></td>
                                <td><span class="fw-bold"><?= htmlspecialchars($dir['nom_direction']) ?></span></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-light text-primary border px-3">
                                        <?= $dir['nb_stagiaires'] ?> actif(s)
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEdit<?= $dir['id_direction'] ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        
                                        <a href="dashboard.php?page=directions&delete_dir=<?= $dir['id_direction'] ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Supprimer définitivement cette direction ?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>

                                    <div class="modal fade" id="modalEdit<?= $dir['id_direction'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning text-dark">
                                                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Modifier Direction</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body text-start">
                                                        <input type="hidden" name="id_direction" value="<?= $dir['id_direction'] ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold text-muted">Nouveau nom de la direction</label>
                                                            <input type="text" name="nom_direction" class="form-control" value="<?= htmlspecialchars($dir['nom_direction']) ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" name="editer_direction" class="btn btn-warning btn-sm fw-bold">ENREGISTRER LES MODIFICATIONS</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>