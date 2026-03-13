<?php
require_once '../includes/config.php';

if (!isset($_GET['id'])) {
    die("ID du stage manquant.");
}

$id_stage = intval($_GET['id']);

try {
    $sql = "SELECT s.*, d.nom_etudiant, d.prenom_etudiant, d.genre, 
                   dir.nom_direction, u.nom_user as nom_maitre, u.prenom_user as prenom_maitre
            FROM stages s
            JOIN demandes d ON s.id_demande = d.id_demande
            JOIN directions dir ON s.id_direction = dir.id_direction
            JOIN utilisateurs u ON s.id_maitre = u.id_user
            WHERE s.id_stage = ? AND s.etat_stage = 'Terminé'";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_stage]);
    $data = $stmt->fetch();

    if (!$data) {
        die("Stage non trouvé ou non encore clôturé.");
    }

    $civilite = ($data['genre'] == 'M') ? 'Monsieur' : 'Madame';
    $date_debut = date('d/m/Y', strtotime($data['date_debut']));
    $date_fin = date('d/m/Y', strtotime($data['date_fin']));
    $date_aujourdhui = date('d/m/Y');

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation Officielle - <?= htmlspecialchars($data['nom_etudiant']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @page { size: A4 landscape; margin: 0; }
        :root {
            --ci-orange: #FF8200;
            --ci-green: #009E60;
            --mffe-blue: #0d47a1;
        }

        body { 
            font-family: 'Cambria', serif; 
            margin: 0; padding: 0; background: #f4f4f4;
            -webkit-print-color-adjust: exact;
        }

        .diploma-container {
            width: 29.7cm; 
            height: 21cm;
            background-color: #fff;
            margin: 0.5cm auto;
            position: relative;
            box-sizing: border-box;
            border: 15px solid transparent;
            border-image: linear-gradient(to bottom right, var(--ci-orange), #fff, var(--ci-green)) 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .diploma-content { 
            padding: 0.8cm 1.5cm; 
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        
        .header-table { width: 100%; border-collapse: collapse; }
        .logo-img { height: 85px; width: auto; }
        .ministere-name { font-size: 10pt; font-weight: bold; color: var(--mffe-blue); text-transform: uppercase; margin-top: 5px; line-height: 1.2; }
        .drh-sub { font-size: 9pt; font-weight: bold; border-bottom: 2px solid var(--ci-orange); display: inline-block; margin-top: 3px; }

        
        .flag-rule {
            display: flex;
            width: 140px;
            height: 7px;
            margin: 8px 0 0 auto;
            border: 0.5px solid #ddd;
        }
        .flag-rule .orange { background: #FF8200; flex: 1; }
        .flag-rule .white { background: #FFFFFF; flex: 1; }
        .flag-rule .green { background: #009E60; flex: 1; }

        
        .title-area { text-align: center; margin: 10px 0; }
        .main-title { font-size: 32pt; font-weight: bold; color: var(--mffe-blue); text-transform: uppercase; letter-spacing: 5px; margin: 0; }

        
        .text-content { font-size: 13.5pt; text-align: justify; line-height: 1.6; padding: 0 1cm; }
        .student-block { text-align: center; margin: 20px 0; }
        .student-name { font-size: 24pt; font-weight: bold; border-bottom: 3px solid var(--ci-green); padding: 0 20px; display: inline-block; color: #000; }
        .theme-box { font-style: italic; font-weight: bold; text-align: center; padding: 15px; background: #f9f9f9; border: 1px solid #eee; margin: 15px 100px; border-radius: 8px; }

        
        .footer-area { margin-top: 10px; }
        .date-line { text-align: right; font-size: 12pt; margin-bottom: 15px; font-weight: bold; padding-right: 1cm; }
        .signature-table { width: 100%; table-layout: fixed; }
        .sig-cell { text-align: center; vertical-align: top; }
        .sig-title { font-weight: bold; text-decoration: underline; font-size: 11pt; line-height: 1.3; display: block; height: 45px; }
        .sig-space { height: 80px; margin: 5px 0; }

        .no-print { background: #333; padding: 10px; text-align: center; }
        .btn-print { padding: 12px 30px; background: var(--ci-green); color: #fff; border: none; cursor: pointer; font-weight: bold; border-radius: 5px; font-size: 1.1rem; }

        @media print {
            .no-print { display: none; }
            .diploma-container { margin: 0; border-width: 15px; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print"><i class="bi bi-printer-fill me-2"></i> IMPRIMER L'ATTESTATION OFFICIELLE</button>
    </div>

    <div class="diploma-container">
        <div class="diploma-content">
            
            <table class="header-table">
                <tr>
                    <td style="width:60%">
                        <img src="../img/logo_mffe_files/MFFE.jpg" class="logo-img" onerror="this.src='https://via.placeholder.com/200x85?text=LOGO+MFFE'">
                        <br>
                        <div class="ministere-name">Ministère de la Femme, de la Famille <br>et de l’Enfant</div>
                        <div class="drh-sub">Direction des Ressources Humaines</div>
                    </td>
                    <td style="width:40%; text-align:right; vertical-align: top;">
                        <strong style="font-size:11pt; text-transform: uppercase;">République de Côte d'Ivoire</strong><br>
                        <small style="font-size:9pt; font-style: italic; letter-spacing: 1px;">Union - Discipline - Travail</small>
                        <div class="flag-rule">
                            <div class="orange"></div>
                            <div class="white"></div>
                            <div class="green"></div>
                        </div>
                        <div style="font-size:9pt; margin-top:15px; font-weight: bold;">N°___________/MFFE/CAB/DRH/ka</div>
                    </td>
                </tr>
            </table>

            <div class="main-body">
                <div class="title-area">
                    <h1 class="main-title">Attestation de Stage</h1>
                </div>

                <div class="text-content">
                    <p>Le Directeur des Ressources Humaines du Ministère de la Femme, de la Famille et de l'Enfant certifie que :</p>
                    
                    <div class="student-block">
                        <span class="student-name"><?= $civilite ?> <?= strtoupper(htmlspecialchars($data['nom_etudiant'])) ?> <?= htmlspecialchars($data['prenom_etudiant']) ?></span>
                    </div>

                    <p>A effectué un stage de Validation au sein de la <strong><?= htmlspecialchars($data['nom_direction']) ?></strong>, 
                    du <strong><?= $date_debut ?></strong> au <strong><?= $date_fin ?></strong>.</p>

                    <div class="theme-box">
                        Thème : « <?= htmlspecialchars($data['theme_stage']) ?> »
                    </div>

                    <p style="text-align: center; margin-top: 15px;">En foi de quoi, la présente attestation lui est délivrée pour servir et valoir ce que de droit.</p>
                </div>
            </div>

            <div class="footer-area">
                <div class="date-line">Fait à Abidjan, le <?= $date_aujourdhui ?></div>

                <table class="signature-table">
                    <tr>
                        <td class="sig-cell">
                            <span class="sig-title">Le Maître de Stage</span>
                            <div class="sig-space"></div>
                            <strong><?= htmlspecialchars($data['prenom_maitre'] . ' ' . $data['nom_maitre']) ?></strong><br>
                            <small>Encadreur Technique</small>
                        </td>
                        <td class="sig-cell">
                            <span class="sig-title">P/ Le Ministre et par délégation,<br>Le Directeur des Ressources Humaines</span>
                            <div class="sig-space"></div>
                            <strong>BARRO K. Yacouba</strong><br>
                            <small>Administrateur Civil</small>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>

</body>
</html>