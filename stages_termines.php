<?php
// On récupère les stages dont l'état est 'Terminé'
$sql = "SELECT s.*, d.nom_etudiant, d.prenom_etudiant, u.nom_user as maitre, dir.nom_direction 
        FROM stages s
        JOIN demandes d ON s.id_demande = d.id_demande
        JOIN utilisateurs u ON s.id_maitre = u.id_user
        JOIN directions dir ON s.id_direction = dir.id_direction
        WHERE s.etat_stage = 'Terminé'
        ORDER BY s.date_fin DESC";

try {
    $stmt = $pdo->query($sql);
    $termines = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erreur SQL : " . $e->getMessage() . "</div>";
    $termines = [];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0 text-dark">Archives des Stages</h4>
    <span class="badge bg-secondary px-3 py-2">Total terminés : <?= count($termines) ?></span>
</div>

<div class="row g-4">
    <?php if(empty($termines)): ?>
        <div class="col-12">
            <div class="card-custom text-center py-5">
                <i class="bi bi-archive text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">Aucun stage n'est encore archivé comme terminé.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach($termines as $t): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card-custom border-top border-4 border-success shadow-sm h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-0"><?= strtoupper(htmlspecialchars($t['nom_etudiant'])) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($t['prenom_etudiant']) ?></small>
                    </div>
                    <span class="badge bg-success-subtle text-success">Complété</span>
                </div>
                
                <div class="small mb-3">
                    <div class="text-dark fw-semibold"><i class="bi bi-journal-check me-2 text-success"></i>Thème :</div>
                    <div class="text-muted italic ps-4">"<?= htmlspecialchars($t['theme_stage']) ?>"</div>
                </div>

                <div class="bg-light p-2 rounded mb-3 small">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Encadreur:</span>
                        <span class="fw-bold text-dark"><?= htmlspecialchars($t['maitre']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Direction:</span>
                        <span class="text-dark"><?= htmlspecialchars($t['nom_direction']) ?></span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                    <div class="small text-muted">
                        <i class="bi bi-calendar-event me-1"></i> Fini le <?= date('d/m/Y', strtotime($t['date_fin'])) ?>
                    </div>
                    <a href="generer_attestation.php?id=<?= $t['id_stage'] ?>" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-patch-check"></i> Attestation
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>