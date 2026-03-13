<?php

$conn = mysqli_connect("localhost", "root", "", "mffe_gestion");

if (!$conn) {
    die("Échec de la connexion : " . mysqli_connect_error());
}

$message = ""; 
$classe = "alert-secondary"; 
$icone = "bi-info-circle"; 
$afficher_barre = false;
$trouve = false;
$progression = 0;
$peut_telecharger = false; 

if (isset($_POST['email']) && !empty($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    $sql = "SELECT d.*, r.motif_rejet 
            FROM demandes d 
            LEFT JOIN rejets r ON d.id_demande = r.id_demande 
            WHERE d.email_etudiant = '$email' 
            LIMIT 1";
            
    $res_demande = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($res_demande) > 0) {
        $trouve = true;
        $infos = mysqli_fetch_assoc($res_demande);
        $id_demande = $infos['id_demande'];
        $statut_actuel = trim($infos['statut_demande']); 
        $nom_etudiant = $infos['nom_etudiant'] . " " . $infos['prenom_etudiant'];

        $res_stage = mysqli_query($conn, "SELECT * FROM stages WHERE id_demande = '$id_demande' LIMIT 1");
        
        if (mysqli_num_rows($res_stage) > 0) {
            $stage = mysqli_fetch_assoc($res_stage);
            $progression = intval($stage['progression']);
            $afficher_barre = true;

            if ($progression >= 100) {
                $message = "🎉 <b>Félicitations ! Votre stage est terminé.</b><br>Votre attestation de fin de stage est désormais disponible.";
                $classe = "alert-success text-dark"; 
                $icone = "bi-trophy-fill";
                $peut_telecharger = true; 
            } else {
                $message = "💼 <b>Votre stage est en cours</b>.<br>Thème : <i>" . htmlspecialchars($stage['theme_stage']) . "</i>";
                $classe = "alert-info text-dark"; 
                $icone = "bi-briefcase-fill";
                $peut_telecharger = false;
            }
        } 
        else {
            $afficher_barre = false;
            
            if ($statut_actuel == 'En attente') {
                $message = "Bonjour <b>$nom_etudiant</b>, votre demande est actuellement <b>en attente</b> de traitement.";
                $classe = "alert-warning text-dark";
                $icone = "bi-hourglass-split";
            } 
            elseif ($statut_actuel == 'Refusé') {
                $message = "Désolé <b>$nom_etudiant</b>, votre demande n'a pas été retenue.";
                $classe = "alert-danger";
                $icone = "bi-x-circle-fill";
                
                if(!empty($infos['motif_rejet'])) {
                    $message .= "<div class='mt-3 p-2 bg-white rounded text-dark shadow-sm' style='border-left: 4px solid #dc3545;'>";
                    // Modification effectuée ici : "MOTIF DU REJET" devient "MOTIF"
                    $message .= "<b>MOTIF :</b><br>" . htmlspecialchars($infos['motif_rejet']);
                    $message .= "</div>";
                }
            } 
            elseif ($statut_actuel == 'Validé') {
                $message = "Félicitations <b>$nom_etudiant</b> ! Votre dossier est <b>Validé</b>. Votre fiche de stage est en cours de création.";
                $classe = "alert-success";
                $icone = "bi-check-circle-fill";
            }
        }
    } else {
        $message = "Aucun dossier trouvé pour l'adresse : <b>" . htmlspecialchars($email) . "</b>";
        $classe = "alert-dark";
        $icone = "bi-search";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>État de votre Dossier | MFFE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; display: flex; align-items: center; min-height: 100vh; font-family: 'Segoe UI', sans-serif; }
        .result-card { max-width: 550px; margin: auto; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); border: none; }
        .card-header { background: #1a1a1a; color: white; font-weight: bold; text-align: center; padding: 20px; border-radius: 20px 20px 0 0; }
        .progress { height: 12px; border-radius: 10px; background-color: rgba(0,0,0,0.05); }
        .btn-return { background-color: #003366; color: white; border: none; transition: 0.3s; }
        .btn-return:hover { background-color: #FF8200; color: white; transform: translateY(-2px); }
        .btn-download { background-color: #009E60; color: white; border: none; font-weight: bold; transition: 0.3s; }
        .btn-download:hover { background-color: #007a4a; color: white; transform: scale(1.02); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card result-card">
        <div class="card-header text-uppercase">Suivi de candidature MFFE</div>
        <div class="card-body p-4 text-center">
            
            <div class="alert <?= $classe ?> p-4 mb-4 shadow-sm border-0">
                <i class="bi <?= $icone ?> display-4 d-block mb-3"></i>
                <span class="fs-5"><?= $message ?></span>

                <?php if ($afficher_barre): ?>
                <div class="mt-4 text-start">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="fw-bold text-uppercase">Progression du stage</small>
                        <small class="fw-bold"><?= $progression ?>%</small>
                    </div>
                    <div class="progress shadow-sm">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" 
                             style="width: <?= $progression ?>%; background-color: <?= ($progression >= 100) ? '#009E60' : '#007bff' ?>;">
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($peut_telecharger): ?>
            <div class="mb-4">
                <a href="generer_attestation.php?id=<?= $stage['id_stage'] ?>" target="_blank" class="btn btn-download btn-lg w-100 rounded-pill shadow">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i>TÉLÉCHARGER MON ATTESTATION
                </a>
                <p class="text-muted small mt-2">Document officiel généré par la DRH.</p>
            </div>
            <?php endif; ?>

            <?php if ($trouve): ?>
            <div class="text-start bg-light p-3 rounded border mb-4">
                <div class="row mb-2">
                    <div class="col-5 text-muted small">NOM ET PRÉNOMS :</div>
                    <div class="col-7 fw-bold"><?= strtoupper($nom_etudiant) ?></div>
                </div>
                <div class="row">
                    <div class="col-5 text-muted small">RÉFÉRENCE :</div>
                    <div class="col-7 fw-bold">#DS-<?= str_pad($id_demande, 3, '0', STR_PAD_LEFT) ?></div>
                </div>
            </div>
            <?php endif; ?>

            <a href="suivi_demande.php" class="btn btn-return btn-lg w-100 rounded-pill shadow-sm py-2 text-uppercase">
                <i class="bi bi-arrow-left-circle me-2"></i>Refaire une recherche
            </a>
        </div>
    </div>
</div>

</body>
</html>