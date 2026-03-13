<?php
require_once 'includes/config.php'; 

if (isset($_GET['id_stage'])) {
    $id_stage = intval($_GET['id_stage']);

    try {
        $pdo->beginTransaction();

        
        $sqlInsert = "INSERT INTO archives (nom_complet, ecole, direction, maitre_stage, theme_stage, date_fin_reelle)
                      SELECT 
                          CONCAT(d.nom_etudiant, ' ', d.prenom_etudiant), 
                          d.etablissement, 
                          dir.nom_direction, 
                          CONCAT(u.nom_user, ' ', u.prenom_user), 
                          s.theme_stage, 
                          s.date_fin
                      FROM stages s
                      JOIN demandes d ON s.id_demande = d.id_demande
                      JOIN directions dir ON s.id_direction = dir.id_direction
                      JOIN utilisateurs u ON s.id_maitre = u.id_user
                      WHERE s.id_stage = ?";
        
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([$id_stage]);

        
        $sqlDelete = "DELETE FROM stages WHERE id_stage = ?";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->execute([$id_stage]);

        $pdo->commit();
        header("Location: archives.php?msg=Stage archivé avec succès");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erreur lors de l'archivage : " . $e->getMessage());
    }
}
?>