<?php
require_once '../includes/config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        
        $check = $pdo->prepare("SELECT COUNT(*) FROM stages WHERE id_maitre = ?");
        $check->execute([$id]);
        $total_historique = $check->fetchColumn();

        if ($total_historique > 0) {
            
             
             
            
            header("Location: ../pages/dashboard.php?page=maitres&error=has_history");
            exit();
        }

        
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_user = ? AND role = 'maitre'");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            header("Location: ../pages/dashboard.php?page=maitres&msg=deleted");
        } else {
            header("Location: ../pages/dashboard.php?page=maitres&error=not_found");
        }
        exit();

    } catch (PDOException $e) {
        
        header("Location: ../pages/dashboard.php?page=maitres&error=sql_error");
        exit();
    }
} else {
    header("Location: ../pages/dashboard.php?page=maitres");
    exit();
}