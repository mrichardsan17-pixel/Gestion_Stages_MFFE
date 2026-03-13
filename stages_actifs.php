<?php
// 1. Initialisation pour éviter les erreurs "Undefined variable"
$stages_actifs = [];

// Protection : On vérifie que la variable $pdo existe
if (!isset($pdo)) {
    die("Accès direct interdit.");
}

// 2. Récupération des stages avec les bonnes jointures
try {
    $sql = "SELECT s.*, 
                   d.nom_etudiant, d.prenom_etudiant, d.telephone_etudiant, d.email_etudiant,
                   dir.nom_direction,
                   u.nom_user as maitre_nom, u.prenom_user as maitre_prenom,
                   e.nom_ecole -- On récupère le nom depuis la table ecoles
            FROM stages s
            JOIN demandes d ON s.id_demande = d.id_demande
            JOIN directions dir ON s.id_direction = dir.id_direction
            LEFT JOIN ecoles e ON d.id_ecole = e.id_ecole -- Jointure pour l'école
            LEFT JOIN utilisateurs u ON s.id_maitre = u.id_user
            WHERE s.etat_stage = 'En cours'
            ORDER BY s.progression DESC";
            
    $stmt = $pdo->query($sql);
    $stages_actifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Si la table 'ecoles' n'existe pas ou le champ est différent, on fait une requête de secours
    $sql_fallback = "SELECT s.*, d.nom_etudiant, d.prenom_etudiant, dir.nom_direction, u.nom_user as maitre_nom, u.prenom_user as maitre_prenom
                     FROM stages s
                     JOIN demandes d ON s.id_demande = d.id_demande
                     JOIN directions dir ON s.id_direction = dir.id_direction
                     LEFT JOIN utilisateurs u ON s.id_maitre = u.id_user
                     WHERE s.etat_stage = 'En cours'";
    $stmt = $pdo->query($sql_fallback);
    $stages_actifs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi bi-hourglass-split text-warning me-2"></i>Stages en cours</h3>
    <span class="badge bg-primary px-3 py-2">
        <?= (is_array($stages_actifs)) ? count($stages_actifs) : 0 ?> Stagiaire(s) actif(s)
    </span>
</div>

<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="table-responsive p-3">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Stagiaire</th>
                    <th>Direction / École</th>
                    <th>Thème du stage</th>
                    <th>Progression</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($stages_actifs)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Aucun stage actif pour le moment.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($stages_actifs as $row): 
                        $prog = round($row['progression']);
                        $color = ($prog >= 100) ? 'bg-success' : (($prog > 50) ? 'bg-primary' : 'bg-warning');
                        $modalId = "detailsModal" . $row['id_stage'];
                    ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($row['nom_etudiant'] . ' ' . $row['prenom_etudiant']) ?></div>
                                <small class="text-muted"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($row['telephone_etudiant'] ?? 'N/A') ?></small>
                            </td>
                            <td>
                                <div class="badge bg-info text-dark mb-1"><?= htmlspecialchars($row['nom_direction']) ?></div><br>
                                <small class="text-muted"><i class="bi bi-building me-1"></i><?= htmlspecialchars($row['nom_ecole'] ?? 'École non renseignée') ?></small>
                            </td>
                            <td><small class="text-wrap" style="max-width: 200px; display: block;"><?= htmlspecialchars($row['theme_stage'] ?? 'Non défini') ?></small></td>
                            <td style="width: 180px;">
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1" style="height: 8px; border-radius: 10px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated <?= $color ?>" 
                                             role="progressbar" style="width: <?= $prog ?>%;"></div>
                                    </div>
                                    <span class="ms-2 fw-bold small"><?= $prog ?>%</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">
                                    <i class="bi bi-eye-fill me-1"></i> Détails
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                                    <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                                        <h5 class="modal-title fw-bold">Fiche Stagiaire</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="small text-muted d-block">ÉTUDIANT</label>
                                            <span class="h5 fw-bold text-dark"><?= strtoupper($row['nom_etudiant']) ?> <?= $row['prenom_etudiant'] ?></span>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <label class="small text-muted d-block">ÉCOLE</label>
                                                <span class="fw-bold"><?= htmlspecialchars($row['nom_ecole'] ?? 'N/A') ?></span>
                                            </div>
                                            <div class="col-6">
                                                <label class="small text-muted d-block">CONTACT</label>
                                                <span class="fw-bold"><?= htmlspecialchars($row['telephone_etudiant'] ?? 'N/A') ?></span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="small text-muted d-block">THÈME DE STAGE</label>
                                            <p class="p-2 bg-light rounded border small"><?= htmlspecialchars($row['theme_stage'] ?? 'Aucun thème défini') ?></p>
                                        </div>
                                        <div class="p-3 bg-primary bg-opacity-10 rounded">
                                            <label class="small text-muted d-block text-primary fw-bold">ENCADREUR AFFECTÉ</label>
                                            <span class="fw-bold"><i class="bi bi-person-check me-2"></i><?= $row['maitre_nom'] ? strtoupper($row['maitre_nom']).' '.$row['maitre_prenom'] : 'En attente...' ?></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>