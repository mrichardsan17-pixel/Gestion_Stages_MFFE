<?php
session_start();
require_once '../includes/config.php';

// Sécurité : Vérifier si l'utilisateur est bien connecté en tant que maître
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_stage']) && isset($_POST['progression'])) {
    
    $id_stage = intval($_POST['id_stage']);
    $nouvelle_progression = intval($_POST['progression']);

    // Validation : La progression doit être comprise entre 0 et 100
    if ($nouvelle_progression < 0) $nouvelle_progression = 0;
    if ($nouvelle_progression > 100) $nouvelle_progression = 100;

    try {
        // Optionnel : Vérifier que ce stage appartient bien à ce maître de stage
        // pour empêcher un utilisateur de modifier le stage d'un collègue via l'ID
        $check = $pdo->prepare("SELECT id_stage FROM stages WHERE id_stage = ? AND id_maitre = ?");
        $check->execute([$id_stage, $_SESSION['user_id']]);
        
        if ($check->rowCount() === 0) {
            header("Location: maitre_dashboard.php?status=unauthorized");
            exit();
        }

        // Mise à jour de la progression
        $sql = "UPDATE stages SET progression = :prog WHERE id_stage = :id";
        $stmt = $pdo->prepare($sql);
        
        $resultat = $stmt->execute([
            'prog' => $nouvelle_progression,
            'id'   => $id_stage
        ]);

        if ($resultat) {
            header("Location: maitre_dashboard.php?page=mes_stagiaires&status=success");
            exit();
        }

    } catch (PDOException $e) {
        // Log de l'erreur en production, affichage en développement
        header("Location: maitre_dashboard.php?page=mes_stagiaires&status=error");
        exit();
    }
} else {
    header("Location: maitre_dashboard.php");
    exit();
}