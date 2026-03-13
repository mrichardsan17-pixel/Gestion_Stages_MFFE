<?php
// On n'inclut pas config.php ici car il est déjà dans dashboard.php via l'include

if (isset($_GET['id_action']) && isset($_GET['type'])) {
    $id = intval($_GET['id_action']);
    $type = $_GET['type'];
    $motif = isset($_GET['motif']) ? $_GET['motif'] : null;

    try {
        $pdo->beginTransaction();

        if ($type === 'Valider') {
            $stmt = $pdo->prepare("UPDATE demandes SET statut_demande = 'Validé' WHERE id_demande = ?");
            $stmt->execute([$id]);
        } 
        elseif ($type === 'Rejeter' && $motif !== null) {
            $stmt = $pdo->prepare("UPDATE demandes SET statut_demande = 'Refusé' WHERE id_demande = ?");
            $stmt->execute([$id]);

            $stmtRejet = $pdo->prepare("INSERT INTO rejets (id_demande, motif_rejet) 
                                        VALUES (?, ?) 
                                        ON DUPLICATE KEY UPDATE motif_rejet = ?, date_decision = NOW()");
            $stmtRejet->execute([$id, $motif, $motif]);
        }

        $pdo->commit();
        echo "<script>window.location.href='dashboard.php?page=candidatures&msg=success';</script>";
        exit();

    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        echo "<div class='alert alert-danger'>Erreur : " . $e->getMessage() . "</div>";
    }
}

// Récupération des demandes
$demandes = $pdo->query("SELECT d.*, e.nom_ecole 
                         FROM demandes d 
                         LEFT JOIN ecoles e ON d.id_ecole = e.id_ecole 
                         ORDER BY d.id_demande DESC")->fetchAll();
?>

<div class="card shadow-sm border-0 mt-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="small text-uppercase fw-bold text-muted">
                    <th class="ps-3">Candidat</th>
                    <th>École</th>
                    <th class="text-center">Documents</th> 
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($demandes as $d): 
                    $s = $d['statut_demande'];
                    $badgeColor = ($s == 'Validé') ? 'success' : (($s == 'Refusé') ? 'danger' : 'warning');
                ?>
                <tr>
                    <td class="ps-3">
                        <div class="fw-bold"><?= htmlspecialchars($d['nom_etudiant'] . ' ' . $d['prenom_etudiant']) ?></div>
                        <div class="small text-muted"><?= htmlspecialchars($d['email_etudiant']) ?></div>
                    </td>
                    <td><span class="text-muted"><?= htmlspecialchars($d['nom_ecole'] ?? 'N/A') ?></span></td>
                    
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <?php if(!empty($d['cv_path'])): ?>
                                <a href="../uploads/documents/<?= htmlspecialchars($d['cv_path']) ?>" target="_blank" class="btn btn-sm btn-outline-danger" title="Voir le CV">
                                    <i class="bi bi-file-earmark-pdf"></i> CV
                                </a>
                            <?php endif; ?>

                            <?php if(!empty($d['lettre_motivation'])): ?>
                                <a href="../uploads/documents/<?= htmlspecialchars($d['lettre_motivation']) ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="Voir la Lettre">
                                    <i class="bi bi-file-earmark-text"></i> LM
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>

                    <td>
                        <span class="badge bg-<?= $badgeColor ?> bg-opacity-10 text-<?= $badgeColor ?> rounded-pill">
                            <?= $s ?: 'En attente' ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <?php if(empty($s) || $s == 'En attente'): ?>
                            <div class="btn-group">
                                <a href="dashboard.php?page=candidatures&id_action=<?= $d['id_demande'] ?>&type=Valider" 
                                   class="btn btn-sm btn-success" onclick="return confirm('Valider ce dossier ?')">
                                    <i class="bi bi-check-circle"></i>
                                </a>

                                <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-bs-toggle="dropdown">
                                    Rejeter
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li class="dropdown-header small text-uppercase fw-bold text-muted">Choisir un motif</li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item small" href="dashboard.php?page=candidatures&id_action=<?= $d['id_demande'] ?>&type=Rejeter&motif=Dossier incomplet">Dossier incomplet</a></li>
                                    <li><a class="dropdown-item small" href="dashboard.php?page=candidatures&id_action=<?= $d['id_demande'] ?>&type=Rejeter&motif=Profil non conforme">Profil non conforme</a></li>
                                    <li><a class="dropdown-item small" href="dashboard.php?page=candidatures&id_action=<?= $d['id_demande'] ?>&type=Rejeter&motif=Capacité d'accueil atteinte">Capacité d'accueil atteinte</a></li>
                                    <li><a class="dropdown-item small" href="dashboard.php?page=candidatures&id_action=<?= $d['id_demande'] ?>&type=Rejeter&motif=Période de stage non disponible">Période non disponible</a></li>
                                    <li><a class="dropdown-item small" href="dashboard.php?page=candidatures&id_action=<?= $d['id_demande'] ?>&type=Rejeter&motif=Absence de convention de stage">Absence de convention</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <span class="text-muted small"><i class="bi bi-check2-all"></i> Traité</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>