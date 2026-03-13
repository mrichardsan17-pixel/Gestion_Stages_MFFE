<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil | Portail des Stages MFFE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { 
            --mffe-orange: #FF8200; 
            --mffe-green: #009E60; 
            --mffe-blue: #003366; 
        }

        body { 
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
            margin: 0; padding: 0; 
            height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
            overflow: hidden;
        }

        /* HEADER AVEC LOGOS AUX EXTRÉMITÉS */
        .official-header {
            background: #fff;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .logo-mffe { height: 95px; }
        .logo-armoiries { height: 95px; }

        .republique-text h5 {
            font-size: 1.6rem;
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            color: #000;
        }

        .republique-text p {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            color: #444;
        }

        /* BANDEAU TRICOLORE AGRANDI ET FLUIDE */
        .tricolore-banner {
            background: #fff;
            border-top: 12px solid var(--mffe-orange);
            border-bottom: 12px solid var(--mffe-green);
            text-align: center;
            padding: 25px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .tricolore-banner h2 {
            margin: 0;
            font-size: 2rem;
            font-weight: 900;
            color: var(--mffe-blue);
            text-transform: uppercase;
        }

        /* CONTENU */
        .welcome-header {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--mffe-blue);
            margin: 25px 0;
        }

        .carousel-item-img { height: 180px; width: 100%; object-fit: cover; border-radius: 15px; }

        .action-card {
            background: #fff;
            border-radius: 20px; 
            transition: transform 0.3s ease;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            padding: 30px !important;
            border: none;
        }

        .action-card:hover { transform: translateY(-5px); }

        .card-candidat { border-bottom: 10px solid var(--mffe-orange); }
        .card-suivi { border-bottom: 10px solid var(--mffe-blue); }
        .card-admin { border-bottom: 10px solid var(--mffe-green); }

        .btn-main { 
            font-weight: 800;
            padding: 12px;
            border-radius: 10px;
            text-transform: uppercase;
        }

        .custom-footer { 
            background-color: var(--mffe-blue); 
            color: #fff; 
            padding: 15px 0; 
            margin-top: auto;
            border-top: 5px solid var(--mffe-orange);
        }

        /* STYLE MODALE D'ATTENTION */
        .modal-content { border-radius: 20px; border: none; }
        .modal-header { background-color: #fcf8e3; border-radius: 20px 20px 0 0; }
    </style>
</head>
<body>

<header class="official-header">
    <div class="header-top">
        <img src="img/logo OFFICIEL MFFE_files/MIFFE.jpg" alt="Logo MFFE" class="logo-mffe">
        <div class="republique-text text-center">
            <h5>République de Côte d'Ivoire</h5>
            <p>Union - Discipline - Travail</p>
            <div style="width: 100px; border-bottom: 5px solid var(--mffe-orange); margin: 8px auto;"></div>
        </div>
        <img src="img/armoirie.png" alt="Armoiries" class="logo-armoiries">
    </div>
</header>

<div class="tricolore-banner">
    <h2>Ministère de la Femme, de la Famille et de l'Enfant</h2>
</div>

<main class="container text-center flex-grow-1 d-flex flex-column justify-content-center">
    <h1 class="welcome-header">BIENVENUE SUR LA PLATE-FORME OFFICIELLE DE GESTION DES STAGES </h1>

    <div class="row g-4 mb-4">
        <div class="col-md-4"><img src="img/etudiante.png" class="carousel-item-img shadow-sm"></div>
        <div class="col-md-4"><img src="img/esatic etudiant.jpg" class="carousel-item-img shadow-sm"></div>
        <div class="col-md-4"><img src="img/ESATIC-105-diplomes.jpg" class="carousel-item-img shadow-sm"></div>
    </div>

    <div class="row g-4 mb-3">
        <div class="col-md-4">
            <div class="card action-card card-candidat">
                <i class="bi bi-person-plus-fill fs-1 text-warning mb-2"></i>
                <h6 class="fw-bold mb-3">NOUVELLE DEMANDE</h6>
                <a href="pages/demande.php" class="btn btn-warning text-white w-100 btn-main">DÉPOSER MON DOSSIER</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card action-card card-suivi">
                <i class="bi bi-search fs-1 mb-2" style="color: var(--mffe-blue);"></i>
                <h6 class="fw-bold mb-3">SUIVRE MON STATUT</h6>
                <a href="pages/suivi_demande.php" class="btn text-white w-100 btn-main" style="background-color: var(--mffe-blue);">CONSULTER</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card action-card card-admin">
                <i class="bi bi-lock-fill fs-1 text-success mb-2"></i> 
                <h6 class="fw-bold mb-3">ESPACE AGENT DRH</h6>
                <button type="button" class="btn btn-success w-100 btn-main" data-bs-toggle="modal" data-bs-target="#warningModal">
                    ACCÈS RÉSERVÉ
                </button>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="warningModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>ACCÈS PROFESSIONNEL</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-start">
                <p class="fw-bold">Attention :</p>
                <p>Cet espace est strictement réservé aux agents habilités de la <strong>Direction des Ressources Humaines (DRH)</strong> du Ministère.</p>
                <p>Toute tentative d'accès non autorisée est enregistrée et peut faire l'objet de poursuites conformément à la législation sur la cybercriminalité.</p>
                <div class="alert alert-warning py-2 mb-0">
                    En cliquant sur "Continuer", vous confirmez être un personnel autorisé.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="pages/login.php" class="btn btn-success">Continuer vers la connexion</a>
            </div>
        </div>
    </div>
</div>

<footer class="custom-footer text-center">
    <div class="container small">
        <p class="mb-0 fw-bold">© 2026 - MINISTÈRE DE LA FEMME, DE LA FAMILLE ET DE L'ENFANT. TOUS DROIT RESERVES</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>