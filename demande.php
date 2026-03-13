<?php 
// Correction du chemin : on remonte d'un cran pour atteindre 'includes' depuis le dossier 'pages'
require_once '../includes/config.php'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidature Stage | MFFE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { 
            --mffe-orange: #FF8200; 
            --mffe-green: #009E60; 
            --mffe-blue: #003366; 
        }
        
        body { 
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
            display: flex; 
            flex-direction: column; 
            min-height: 100vh;
            margin: 0;
        }

        .custom-header {
            background-color: white;
            padding: 10px 0;
            border-bottom: 4px solid var(--mffe-orange);
            overflow: hidden;
        }
        .logo-img { height: 75px; width: auto; }

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
            font-size: 1.3rem;
            text-transform: uppercase;
            padding-left: 100%;
            animation: scroll-left 18s linear infinite;
        }

        @keyframes scroll-left {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }

        .republique-bloc {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .flag-rule {
            display: flex;
            width: 110px;
            height: 6px;
            margin: 5px 0;
            border-radius: 2px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .flag-rule .orange { background: #FF8200; flex: 1; }
        .flag-rule .white { background: #FFFFFF; flex: 1; }
        .flag-rule .green { background: #009E60; flex: 1; }

        .required-star { color: #dc3545; font-weight: bold; margin-left: 4px; }

        .card-main {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 950px;
            margin: 0 auto;
        }
        
        .card-header-custom {
            background: var(--mffe-blue);
            color: white;
            padding: 25px;
            text-align: center;
            border-bottom: 5px solid var(--mffe-orange);
            position: relative;
        }

        .btn-back {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 1.5rem;
            transition: 0.3s;
            text-decoration: none;
        }
        .btn-back:hover {
            color: var(--mffe-orange);
            transform: translateY(-50%) scale(1.1);
        }

        .form-label { font-weight: 700; color: var(--mffe-blue); margin-bottom: 8px; }
        
        .form-control:focus, .form-select:focus { 
            border-color: var(--mffe-orange); 
            box-shadow: 0 0 8px rgba(255, 130, 0, 0.2);
        }

        .btn-submit { 
            background: linear-gradient(45deg, var(--mffe-orange), #ff9f43);
            border: none; 
            color: white; 
            padding: 15px; 
            font-weight: 800; 
            font-size: 1.2rem;
            border-radius: 12px; 
            transition: 0.3s;
            cursor: pointer;
        }
        .btn-submit:hover { 
            background: var(--mffe-blue) !important; 
            transform: translateY(-2px);
        }

        .file-box {
            transition: all 0.3s ease;
            border: 2px dashed #ccc !important;
            background: #fafafa;
        }
        .file-box:hover { border-color: var(--mffe-orange) !important; background: #fff9f2 !important; }

        footer {
            background-color: var(--mffe-green);
            color: white;
            padding: 20px 0;
            margin-top: auto;
        }
    </style>
</head>
<body>

<header class="custom-header shadow-sm">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-2 text-center text-md-start">
                <img src="../img/logo OFFICIEL MFFE_files/MIFFE.jpg" alt="Logo MFFE" class="logo-img" onerror="this.src='https://via.placeholder.com/90x75?text=MFFE'">
            </div>

            <div class="col-md-7">
                <div class="marquee-container">
                    <div class="ministere-defilant">
                        MINISTÈRE DE LA FEMME, DE LA FAMILLE ET DE L'ENFANT — DIRECTION DES RESSOURCES HUMAINES — FORMULAIRE DE DEMANDE DE STAGE 2026
                    </div>
                </div>
            </div>

            <div class="col-md-3 republique-bloc text-center d-none d-md-flex">
                <h6 class="fw-bold mb-0" style="font-size: 0.8rem; letter-spacing: 0.5px; color: #333;">RÉPUBLIQUE DE CÔTE D'IVOIRE</h6>
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

<div class="container my-5">
    <div class="card-main shadow">
        <div class="card-header-custom">
            <a href="../index.php" class="btn-back" title="Retour à l'accueil">
                <i class="bi bi-arrow-left-circle-fill"></i>
            </a>
            <h2 class="mb-0 fw-bold text-uppercase"><i class="bi bi-file-earmark-person-fill me-2"></i> Formulaire de Candidature</h2>
        </div>
        
        <div class="card-body p-4 p-md-5">
            <form action="traitement_demande.php" method="POST" enctype="multipart/form-data" id="formCandidature">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Nom <span class="required-star">*</span></label>
                        <input type="text" name="nom_etudiant" class="form-control" placeholder="EX: SAN" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénom(s) <span class="required-star">*</span></label>
                        <input type="text" name="prenom_etudiant" class="form-control" placeholder="EX: kouassi Richard" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Numéro de Téléphone <span class="required-star">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-white fw-bold">+225</span>
                            <input type="text" name="telephone_etudiant" class="form-control" placeholder="0715166856" maxlength="10" pattern="\d{10}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Adresse Email <span class="required-star">*</span></label>
                        <input type="email" name="email_etudiant" class="form-control" placeholder="votremail@gmail.com" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Établissement / École d'origine <span class="required-star">*</span></label>
                        <select name="id_ecole" id="select_ecole" class="form-select" onchange="toggleAutreEcole(this.value)" required>
                            <option value="" disabled selected>-- Cliquez pour choisir votre école --</option>
                            <?php
                            try {
                                // Assurez-vous que $pdo est bien défini dans config.php
                                $res = $pdo->query("SELECT id_ecole, nom_ecole FROM ecoles ORDER BY nom_ecole ASC");
                                while ($row = $res->fetch()) {
                                    echo "<option value='".$row['id_ecole']."'>".$row['nom_ecole']."</option>";
                                }
                            } catch (Exception $e) {
                                // En cas d'erreur de base de données
                            }
                            ?>
                            <option value="AUTRE" style="font-weight: bold; color: var(--mffe-orange);">+ MON ÉCOLE N'EST PAS DANS LA LISTE</option>
                        </select>
                        <input type="text" name="autre_ecole_nom" id="input_autre_ecole" class="form-control mt-3" style="display:none;" placeholder="Saisissez le nom complet de votre établissement">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Genre <span class="required-star">*</span></label>
                        <select name="genre" class="form-select" required>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Date de soumission</label>
                        <input type="text" class="form-control bg-light" value="<?php echo date('d/m/Y'); ?>" readonly>
                    </div>

                    <div class="col-md-6">
                        <div class="p-4 border rounded file-box text-center shadow-sm">
                            <label class="form-label d-block"><i class="bi bi-file-earmark-pdf-fill text-danger fs-1"></i><br>CV (PDF) <span class="required-star">*</span></label>
                            <input type="file" name="cv_path" class="form-control" accept=".pdf" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 border rounded file-box text-center shadow-sm">
                            <label class="form-label d-block"><i class="bi bi-envelope-paper-fill text-primary fs-1"></i><br>Lettre de Motivation (PDF) <span class="required-star">*</span></label>
                            <input type="file" name="lettre_motivation_path" class="form-control" accept=".pdf" required>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn-submit w-100 shadow">
                            <i class="bi bi-send-fill me-2"></i> TRANSMETTRE MA CANDIDATURE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="text-center">
    <div class="container">
        <p class="mb-0 small">© 2026 - Ministère de la Femme, de la Famille et de l'Enfant. Tous droits réservés.</p>
    </div>
</footer>

<script>
    function toggleAutreEcole(val) {
        const input = document.getElementById('input_autre_ecole');
        input.style.display = (val === 'AUTRE') ? 'block' : 'none';
        if(val === 'AUTRE') input.setAttribute('required', 'required');
        else input.removeAttribute('required');
    }

    document.getElementById('formCandidature').onsubmit = function() {
        const files = document.querySelectorAll('input[type="file"]');
        const limit = 5 * 1024 * 1024; // 5 Mo
        for (let f of files) {
            if (f.files[0] && f.files[0].size > limit) {
                alert("Erreur : Le fichier " + f.files[0].name + " dépasse 5 Mo.");
                return false;
            }
        }
        return true;
    };
</script>
</body>
</html>