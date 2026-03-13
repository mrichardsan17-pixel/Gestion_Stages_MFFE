<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/config.php';

if (!$pdo) { 
    die("Erreur de connexion à la base de données."); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupération des données de base
    $nom = htmlspecialchars($_POST['nom_etudiant'] ?? '');
    $prenom = htmlspecialchars($_POST['prenom_etudiant'] ?? '');
    $email = htmlspecialchars($_POST['email_etudiant'] ?? '');
    $tel = htmlspecialchars($_POST['telephone_etudiant'] ?? '');
    $genre = $_POST['genre'] ?? '';
    
    // On récupère la valeur brute (peut être un ID ou la chaîne "AUTRE")
    $id_ecole_brut = $_POST['id_ecole'] ?? ''; 
    $date_s = !empty($_POST['date_soumission']) ? $_POST['date_soumission'] : date('Y-m-d H:i:s');

    try {
        // --- LOGIQUE POUR LA SAISIE MANUELLE DE L'ÉCOLE ---
        if ($id_ecole_brut === "AUTRE" && !empty($_POST['autre_ecole_nom'])) {
            $nom_nouvelle_ecole = htmlspecialchars(trim($_POST['autre_ecole_nom']));

            // 1. Vérifier si l'école n'existe pas déjà (évite les doublons)
            $check = $pdo->prepare("SELECT id_ecole FROM ecoles WHERE nom_ecole = ?");
            $check->execute([$nom_nouvelle_ecole]);
            $ecole_existante = $check->fetch();

            if ($ecole_existante) {
                $id_ecole = $ecole_existante['id_ecole'];
            } else {
                // 2. Insérer la nouvelle école pour obtenir un ID numérique valide
                $ins_ecole = $pdo->prepare("INSERT INTO ecoles (nom_ecole) VALUES (?)");
                $ins_ecole->execute([$nom_nouvelle_ecole]);
                $id_ecole = $pdo->lastInsertId(); 
            }
        } else {
            // Si une école existante a été choisie, on convertit en entier
            $id_ecole = intval($id_ecole_brut);
        }

        // --- GESTION DES FICHIERS ---
        $dir = "../uploads/documents/";
        if (!is_dir($dir)) { 
            mkdir($dir, 0777, true); 
        }

        $cv_name = time() . "_CV_" . $nom . ".pdf";
        $lettre_name = time() . "_LETTRE_" . $nom . ".pdf"; 

        $cv_recu = (isset($_FILES['cv_path']) && $_FILES['cv_path']['error'] === 0);
        $lettre_recue = (isset($_FILES['lettre_motivation_path']) && $_FILES['lettre_motivation_path']['error'] === 0);

        if ($cv_recu && $lettre_recue) {
            
            $move_cv = move_uploaded_file($_FILES['cv_path']['tmp_name'], $dir . $cv_name);
            $move_lettre = move_uploaded_file($_FILES['lettre_motivation_path']['tmp_name'], $dir . $lettre_name);

            if ($move_cv && $move_lettre) {
                
                // --- INSERTION DANS LA TABLE DEMANDES ---
                // id_ecole est maintenant forcément un entier valide correspondant à la table ECOLES
                $sql = "INSERT INTO demandes (nom_etudiant, prenom_etudiant, email_etudiant, telephone_etudiant, genre, id_ecole, cv_path, lettre_motivation, statut_demande, date_soumission) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'En attente', ?)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nom, $prenom, $email, $tel, $genre, $id_ecole, $cv_name, $lettre_name, $date_s]);

                echo "
                <div style='display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif;'>
                    <div style='text-align: center; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-top: 5px solid #009E60;'>
                        <h2 style='color: #009E60;'>✅ Succès !</h2>
                        <p style='font-size: 1.1rem; color: #333;'>Votre demande a été enregistrée avec succès.</p>
                        <br>
                        <a href='../index.php' style='text-decoration: none; background: #003366; color: white; padding: 12px 25px; border-radius: 8px; font-weight: bold;'>
                            ⬅️ Retour à l'accueil
                        </a>
                    </div>
                </div>";

            } else {
                echo "❌ Erreur : Échec du déplacement des fichiers.";
            }
        } else {
            echo "❌ Fichiers PDF manquants ou invalides.";
        }

    } catch (Exception $e) {
        // En cas d'erreur de contrainte (1452), ce message s'affichera
        echo "❌ Erreur système : " . $e->getMessage();
    }
}
?>