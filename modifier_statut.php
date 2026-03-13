<?php

require_once '../includes/config.php';


if (isset($_GET['id']) && isset($_GET['action'])) {
    $id_demande = intval($_GET['id']);
    $action = $_GET['action'];

    
    $nouveau_statut = '';
    switch ($action) {
        case 'Valider':
            $nouveau_statut = 'Validé';
            break;
        case 'Rejeter':
            $nouveau_statut = 'Rejeté';
            break;
        case 'Affecter':
            $nouveau_statut = 'Affecté';
            break;
        default:
            $nouveau_statut = 'En attente';
    }

    if ($id_demande > 0 && $nouveau_statut !== '') {
        try {
            // Préparation de la mise à jour
            $sql = "UPDATE demandes SET statut_demande = ? WHERE id_demande = ?";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$nouveau_statut, $id_demande])) {
                
                
                header("Location: liste_demandes.php?msg=1");
                exit();
            }
        } catch (PDOException $e) {
            die("Erreur lors de la mise à jour : " . $e->getMessage());
        }
    }
}


header("Location: liste_demandes.php");
exit();
?>