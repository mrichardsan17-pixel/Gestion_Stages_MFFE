<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de Dossier | MFFE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --mffe-blue: #003366;
            --mffe-orange: #FF8200;
            --mffe-green: #009E60;
        }

        body { 
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        /* HEADER AVEC NOM DÉFILANT */
        .custom-header {
            background-color: white;
            padding: 10px 0;
            border-bottom: 4px solid var(--mffe-orange);
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .marquee-container {
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            background: #fdfdfd;
            padding: 5px 0;
        }

        .ministere-defilant {
            display: inline-block;
            font-weight: 800;
            color: var(--mffe-green);
            font-size: 1.2rem;
            text-transform: uppercase;
            padding-left: 100%;
            animation: scroll-left 18s linear infinite;
        }

        @keyframes scroll-left {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        /* BLOC RÉPUBLIQUE CENTRÉ */
        .republique-bloc {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .flag-rule {
            display: flex;
            width: 100px;
            height: 6px;
            margin: 5px 0;
            border-radius: 2px;
            overflow: hidden;
        }
        .flag-rule .orange { background: #FF8200; flex: 1; }
        .flag-rule .white { background: #FFFFFF; flex: 1; }
        .flag-rule .green { background: #009E60; flex: 1; }

        /* CONTENU PRINCIPAL */
        .main-content { 
            flex: 1; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 40px 20px;
        }
        
        .tracking-card {
            background: white;
            width: 100%;
            max-width: 480px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border-top: 6px solid var(--mffe-blue);
            text-align: center;
            position: relative; /* Important pour le positionnement du bouton retour */
        }

        /* Style du bouton retour icône */
        .btn-back-top {
            position: absolute;
            left: 15px;
            top: 15px;
            color: #ccc;
            font-size: 1.4rem;
            transition: 0.3s;
        }
        .btn-back-top:hover {
            color: var(--mffe-orange);
            transform: scale(1.1);
        }

        .tracking-card h3 {
            color: var(--mffe-blue);
            font-weight: 800;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .form-label { font-weight: 700; color: #444; }

        .btn-search {
            background: var(--mffe-blue);
            color: white;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 10px;
            font-weight: 800;
            transition: 0.3s;
        }

        .btn-search:hover {
            background: var(--mffe-orange);
            transform: translateY(-2px);
        }

        footer {
            background-color: var(--mffe-green);
            color: white;
            text-align: center;
            padding: 20px;
            font-weight: 600;
            border-top: 5px solid var(--mffe-orange);
            margin-top: auto;
        }
    </style>
</head>
<body>

<header class="custom-header">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-2 text-center text-md-start">
                <img src="../img/logo OFFICIEL MFFE_files/MIFFE.jpg" alt="Logo MFFE" style="height: 70px;" onerror="this.src='https://via.placeholder.com/70'">
            </div>

            <div class="col-md-7">
                <div class="marquee-container">
                    <div class="ministere-defilant">
                        MINISTÈRE DE LA FEMME, DE LA FAMILLE ET DE L'ENFANT — DIRECTION DES RESSOURCES HUMAINES — SUIVI EN LIGNE DES DEMANDES DE STAGE
                    </div>
                </div>
            </div>

            <div class="col-md-3 republique-bloc d-none d-md-flex">
                <h6 class="fw-bold mb-0" style="font-size: 0.8rem; color: #333;">RÉPUBLIQUE DE CÔTE D'IVOIRE</h6>
                <div class="flag-rule">
                    <div class="orange"></div>
                    <div class="white"></div>
                    <div class="green"></div>
                </div>
                <small class="fw-bold text-muted" style="font-size: 0.65rem;">Union - Discipline - Travail</small>
            </div>
        </div>
    </div>
</header>

<main class="main-content">
    <div class="tracking-card">
        <a href="../index.php" class="btn-back-top" title="Retour à l'accueil">
            <i class="bi bi-arrow-left-circle-fill"></i>
        </a>

        <div class="mb-4">
            <i class="bi bi-search-heart display-4" style="color: var(--mffe-orange);"></i>
        </div>
        <h3>Suivre ma demande</h3>
        <p class="text-muted mb-4">Entrez votre adresse email pour consulter l'état d'avancement de votre dossier de stage.</p>

        <form action="analyse_dossier.php" method="POST">
            <div class="mb-4 text-start">
                <label for="email" class="form-label">Votre adresse Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="exemple@gmail.com" required>
                </div>
            </div>

            <button type="submit" class="btn-search text-uppercase shadow-sm">
                <i class="bi bi-cursor-fill me-2"></i> Analyser mon dossier
            </button>
        </form>
        
        <div class="mt-4 pt-2 border-top">
            <a href="../index.php" class="text-decoration-none text-muted small fw-bold">
                <i class="bi bi-house-door-fill me-1"></i> Quitter et revenir à l'accueil
            </a>
        </div>
    </div>
</main>

<footer>
    <div class="container">
        <p class="mb-0">© <?= date('Y') ?> | MFFE - Direction des Systèmes d'Information</p>
    </div>
</footer>

</body>
</html>