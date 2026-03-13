<?php
session_start();
require_once '../includes/config.php'; 

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_saisi = htmlspecialchars(trim($_POST['email']));
    $mdp_saisi = $_POST['password'];

    if (!empty($email_saisi) && !empty($mdp_saisi)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? LIMIT 1");
            $stmt->execute([$email_saisi]);
            $user = $stmt->fetch();

            // Note : Pour une sécurité réelle, utilisez password_verify() si les mots de passe sont hachés
            if ($user && ($mdp_saisi === $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['nom_user'] = $user['nom_user'];
                $_SESSION['prenom'] = $user['prenom_user'];
                $_SESSION['role'] = $user['role']; 

                if ($user['role'] === 'MAITRE') {
                    header("Location: maitre_dashboard.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                $erreur = "Identifiants incorrects.";
            }
        } catch (PDOException $e) {
            $erreur = "Erreur de base de données.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Pro Agrandi | MFFE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --mffe-blue: #003366;
            --mffe-orange: #FF8200;
            --mffe-green: #009E60;
        }

        body { 
            margin: 0; min-height: 100vh; display: flex; flex-direction: column;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f7f6;
        }

        /* HEADER INSTITUTIONNEL AGRANDI */
        .custom-header {
            background: white; padding: 15px 0; border-bottom: 5px solid var(--mffe-orange);
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        /* LOGO PLUS GRAND */
        .logo-img { height: 90px; width: auto; }

        /* BANDEAU DÉFILANT */
        .marquee-container {
            width: 100%; white-space: nowrap; overflow: hidden; padding: 10px 0;
        }

        .ministere-defilant {
            display: inline-block; font-weight: 800; color: var(--mffe-green);
            font-size: 1.3rem; text-transform: uppercase; padding-left: 100%;
            animation: scroll-left 22s linear infinite;
        }

        @keyframes scroll-left {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        /* BLOC RÉPUBLIQUE ET DRAPEAU AGRANDIS */
        .republique-bloc { display: flex; flex-direction: column; align-items: center; justify-content: center; }
        
        /* Texte République plus grand */
        .republique-title { font-weight: 800; font-size: 1.1rem; color: #222; text-transform: uppercase; margin-bottom: 2px; letter-spacing: 0.5px; }
        
        /* Drapeau plus grand */
        .flag-rule { display: flex; width: 130px; height: 9px; margin: 6px 0; border-radius: 3px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .f-orange { background: var(--mffe-orange); flex: 1; }
        .f-white { background: #FFFFFF; flex: 1; }
        .f-green { background: var(--mffe-green); flex: 1; }
        
        /* Devise plus grande */
        .devise-text { font-weight: 700; color: #555; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; }

        /* CARTE DE CONNEXION */
        .login-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; padding: 30px; }
        .login-card {
            background: white; width: 100%; max-width: 450px;
            border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden; border: 1px solid #eee;
        }

        .card-banner { background: var(--mffe-blue); color: white; padding: 25px; text-align: center; }
        .card-banner h3 { margin: 0; font-weight: 800; text-transform: uppercase; font-size: 1.2rem; }

        .login-body { padding: 40px; }
        .form-label { font-weight: 700; color: var(--mffe-blue); font-size: 0.85rem; text-transform: uppercase; }
        
        .btn-connect {
            background: var(--mffe-orange); color: white; border: none; padding: 15px;
            width: 100%; border-radius: 8px; font-weight: 800; text-transform: uppercase;
            transition: 0.3s; margin-top: 10px;
        }
        .btn-connect:hover { background: #e67500; transform: translateY(-2px); }

        footer {
            background-color: var(--mffe-green); color: white; text-align: center;
            padding: 20px; font-weight: 600; border-top: 5px solid var(--mffe-orange);
        }
    </style>
</head>
<body>

<header class="custom-header">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-2 text-center text-md-start">
                <img src="../img/logo OFFICIEL MFFE_files/MIFFE.jpg" alt="Logo" class="logo-img" onerror="this.src='https://via.placeholder.com/90x90?text=MFFE'">
            </div>
            
            <div class="col-md-7">
                <div class="marquee-container">
                    <div class="ministere-defilant">
                        MINISTÈRE DE LA FEMME, DE LA FAMILLE ET DE L'ENFANT — ACCÈS SÉCURISÉ À L'ESPACE PROFESSIONNEL — GESTA-WEB 2026
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 republique-bloc d-none d-md-flex">
                <div class="republique-title">RÉPUBLIQUE DE CÔTE D'IVOIRE</div>
                <div class="flag-rule">
                    <div class="f-orange"></div>
                    <div class="f-white"></div>
                    <div class="f-green"></div>
                </div>
                <div class="devise-text">Union - Discipline - Travail</div>
            </div>
        </div>
    </div>
</header>

<div class="login-wrapper">
    <div class="login-card">
        <div class="card-banner">
            <h3>ESPACE PROFESSIONNEL</h3>
            <p class="mb-0 small opacity-75">Administration & Encadrement</p>
        </div>
        
        <div class="login-body">
            <?php if ($erreur): ?>
                <div class="alert alert-danger py-2 text-center small mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $erreur; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label class="form-label">Adresse Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person-circle"></i></span>
                        <input type="email" name="email" class="form-control border-start-0" placeholder="admin@mffe.ci" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Mot de Passe</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock"></i></span>
                        <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-connect shadow-sm">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Se Connecter
                </button>
            </form>
            
            <div class="text-center mt-4">
                <a href="../index.php" class="text-muted small text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Retour au portail public
                </a>
            </div>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <p class="mb-0">© 2026 - Ministère de la Femme, de la Famille et de l'Enfant</p>
        <small class="opacity-75">Direction des Systèmes d'Information (DSI)</small>
    </div>
</footer>

</body>
</html>