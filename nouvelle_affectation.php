<?php


$query_candidats = "SELECT id_demande, nom_etudiant, prenom_etudiant 
                    FROM demandes 
                    WHERE statut_demande = 'Validé' 
                    AND id_demande NOT IN (SELECT id_demande FROM stages)";
$candidats = $pdo->query($query_candidats)->fetchAll();


$query_maitres = "SELECT u.id_user, u.nom_user, u.prenom_user, d.id_direction, d.nom_direction 
                  FROM utilisateurs u 
                  JOIN directions d ON u.id_direction = d.id_direction 
                  WHERE u.role = 'MAITRE'";
$maitres = $pdo->query($query_maitres)->fetchAll();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assigner'])) {
    $id_demande = $_POST['id_demande'];
    $id_maitre = $_POST['id_user'];
    $theme = htmlspecialchars($_POST['theme_stage']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $type_stage = $_POST['type_stage'];

    try {
        
        $check = $pdo->prepare("SELECT COUNT(*) FROM stages WHERE id_demande = ?");
        $check->execute([$id_demande]);
        
        if ($check->fetchColumn() > 0) {
            throw new Exception("Ce stagiaire possède déjà une affectation active.");
        }

        $pdo->beginTransaction(); 

        
        $stmt_dir = $pdo->prepare("SELECT id_direction FROM utilisateurs WHERE id_user = ?");
        $stmt_dir->execute([$id_maitre]);
        $id_direction = $stmt_dir->fetchColumn();

        
        $sql = "INSERT INTO stages (id_demande, id_maitre, id_direction, theme_stage, date_debut, date_fin, progression, etat_stage, type_stage) 
                VALUES (?, ?, ?, ?, ?, ?, 0, 'En cours', ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_demande, $id_maitre, $id_direction, $theme, $date_debut, $date_fin, $type_stage]);

        
        $update = $pdo->prepare("UPDATE demandes SET statut_demande = 'Affecté' WHERE id_demande = ?");
        $update->execute([$id_demande]);

        $pdo->commit(); 
        echo "<div class='alert alert-success shadow-sm border-0'><i class='bi bi-check-circle-fill me-2'></i>L'affectation de <b>#DS-".str_pad($id_demande, 3, '0', STR_PAD_LEFT)."</b> a été effectuée avec succès !</div>";
        
        
        $candidats = $pdo->query($query_candidats)->fetchAll();

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack(); 
        }
        echo "<div class='alert alert-danger shadow-sm border-0'><i class='bi bi-exclamation-triangle-fill me-2'></i> Erreur : " . $e->getMessage() . "</div>";
    }
}
?>

<div class="card-custom bg-white p-4 shadow-sm" style="border-radius: 15px; border-left: 5px solid #FF8200;">
    <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-person-plus-fill me-2 text-primary"></i>Nouvelle Affectation</h4>
    <hr>
    
    <form method="POST" class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-bold text-muted small">STAGIAIRE À AFFECTER</label>
            <select name="id_demande" class="form-select border-primary shadow-sm" required>
                <option value="">-- Choisir un candidat validé --</option>
                <?php foreach($candidats as $c): ?>
                    <option value="<?= $c['id_demande'] ?>">
                        <?= strtoupper(htmlspecialchars($c['nom_etudiant'])) ?> <?= htmlspecialchars($c['prenom_etudiant']) ?> (#DS-<?= str_pad($c['id_demande'], 3, '0', STR_PAD_LEFT) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold text-muted small">MAÎTRE DE STAGE ENCADRANT</label>
            <select name="id_user" class="form-select border-primary shadow-sm" required>
                <option value="">-- Choisir un encadreur --</option>
                <?php foreach($maitres as $m): ?>
                    <option value="<?= $m['id_user'] ?>">
                        <?= strtoupper(htmlspecialchars($m['nom_user'])) ?> <?= htmlspecialchars($m['prenom_user']) ?> (<?= htmlspecialchars($m['nom_direction']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-12">
            <label class="form-label fw-bold text-primary small">TYPE DE STAGE (DÉTERMINE LES DATES)</label>
            <select name="type_stage" id="type_stage" class="form-select border-primary shadow-sm fw-bold bg-light-subtle" onchange="updateStageDates()" required>
                <option value="">-- Sélectionner la durée du stage --</option>
                <option value="Qualification (3 mois)">Stage de qualification (3 mois)</option>
                <option value="Perfectionnement (6 mois)">Stage de perfectionnement (6 mois)</option>
            </select>
        </div>

        <div class="col-12">
            <label class="form-label fw-bold text-muted small">THÈME DU STAGE</label>
            <textarea name="theme_stage" class="form-control border-primary shadow-sm" rows="2" placeholder="Sujet de recherche ou mission confiée..." required></textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold text-muted small">DATE DE DÉBUT</label>
            <input type="date" name="date_debut" id="date_debut" class="form-control border-primary shadow-sm bg-light" readonly required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold text-muted small">DATE DE FIN PRÉVUE</label>
            <input type="date" name="date_fin" id="date_fin" class="form-control border-primary shadow-sm bg-light" readonly required>
        </div>

        <div class="col-12 text-end mt-5">
            <button type="reset" class="btn btn-outline-secondary px-4 me-2 rounded-pill">Vider</button>
            <button type="submit" name="assigner" class="btn btn-warning text-dark fw-bold px-5 rounded-pill shadow-sm">
                <i class="bi bi-send-check me-2"></i>Confirmer l'affectation
            </button>
        </div>
    </form>
</div>

<script>

 
 
function updateStageDates() {
    const typeSelect = document.getElementById('type_stage');
    const dateDebutInput = document.getElementById('date_debut');
    const dateFinInput = document.getElementById('date_fin');
    
    if (typeSelect.value === "") {
        dateDebutInput.value = "";
        dateFinInput.value = "";
        return;
    }

    
    const aujourdhui = new Date();
    const debutStr = aujourdhui.toISOString().split('T')[0];
    dateDebutInput.value = debutStr;

    
    let dateFin = new Date(aujourdhui);
    if (typeSelect.value.includes("3 mois")) {
        dateFin.setMonth(dateFin.getMonth() + 3);
    } else if (typeSelect.value.includes("6 mois")) {
        dateFin.setMonth(dateFin.getMonth() + 6);
    }

    dateFinInput.value = dateFin.toISOString().split('T')[0];
}
</script>