<?php
// Connexion à la base de données
$host = 'localhost';
$db = 'medicare';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement AJAX
$correspondance = [
    'head' => ['généraliste', 'dermatologie'],
    'torso-upper' => ['cardiologie', 'addictologie','généraliste'],
    'torso-lower' => ['gastro', 'gynécologie','généraliste','ist'],
    'thigh-left' => ['andrologie', 'gynécologie','généraliste','ist'],
    'thigh-right' => ['andrologie', 'généraliste','ist'],
    'calf-left' => ['ostéopathie','généraliste'],
    'calf-right' => ['ostéopathie','généraliste'],
    'foot-left' => ['dermatologie','généraliste'],
    'foot-right' => ['dermatologie','généraliste'],
    'hand-left' => [ 'dermatologie','généraliste'],
    'hand-right' => ['généraliste'],
    'forearm-left' => ['généraliste'],
    'forearm-right' => ['généraliste']
];

if (isset($_GET['zone'])) {
    $zone = $_GET['zone'];
    $specialites = $correspondance[$zone] ?? [];

    if (!empty($specialites)) {
        // Créer les placeholders pour PDO
        $placeholders = implode(',', array_fill(0, count($specialites), '?'));

        $stmt = $pdo->prepare("SELECT id, nom, specialite, photo FROM medecin WHERE specialite IN ($placeholders)");
        $stmt->execute($specialites);
        $results = $stmt->fetchAll();

        if ($results) {
            foreach ($results as $medecin) {
                echo "<a class='medecin-card' href='fiche_medecin.php?id=" . $medecin['id'] . "'>";
                echo "<img src='" . htmlspecialchars($medecin['photo']) . "' alt='Photo' />";
                echo "<div><strong>" . htmlspecialchars($medecin['nom']) . "</strong><br>";
                echo "<span>" . htmlspecialchars($medecin['specialite']) . "</span></div>";
                echo "</a>";
            }
        } else {
            echo "<p>Aucun médecin trouvé pour cette zone.</p>";
        }
    } else {
        echo "<p>Zone non reconnue.</p>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Exploration du corps</title>
    <link href='https://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet'>
     <link rel="stylesheet" href="style.css" />
    <style>
        body {
            font-family: 'Merriweather', serif;
            margin: 0;
            padding: 0;
        }

        .container-svg {
            max-width: 1000px;
            margin: 80px auto;
            padding: 2rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 40px;
            justify-content: center;
            align-items: flex-start;
            padding: 30px;
            flex-wrap: wrap;
        }

        .svg-zone {
            flex: 1 1 400px;
            max-width: 500px;
            
        }
 

        .result-zone {
            flex: 1 1 400px;
            max-width: 500px;
            background: #f9f9f9;
            border-left: 3px solid #3aaaff;
            padding: 20px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        svg {
            width: auto;
            height: 500px;
            display: block;
            margin-left: 85px;
            
        }

        .outline {
            fill: none;
            stroke: #3d3e3f;
        }

        svg * {
            stroke: #3d3e3f;
            cursor: pointer;
            transition: 800ms ease;
        }

        .hand-right,
        .forearm-right,
        .hand-left,
        .forearm-left,
        .head,
        .torso-upper,
        .torso-lower,
        .thigh-right,
        .calf-right,
        .foot-right,
        .thigh-left,
        .calf-left,
        .foot-left {
            fill: #fff;
        }

        .hand-right:hover,
        .forearm-right:hover,
        .hand-left:hover,
        .forearm-left:hover,
        .head:hover,
        .torso-upper:hover,
        .torso-lower:hover,
        .thigh-right:hover,
        .calf-right:hover,
        .foot-right:hover,
        .thigh-left:hover,
        .calf-left:hover,
        .foot-left:hover {
            fill: #3aaaff;
            stroke: #3aaaff;
        }

        .active-zone {
            fill: #3aaaff !important;
            stroke: #3aaaff !important;
        }

        .medecin-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            margin-bottom: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            text-decoration: none;
            color: inherit;
            transition: background 0.3s;
        }

        .medecin-card:hover {
            background: #f0f8ff;
        }

        .medecin-card img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }

        .medecin-card strong {
            font-size: 1.1em;
            color: #3d3e3f;
        }

        .medecin-card span {
            font-size: 0.95em;
            color: #666;
        }
    </style>
</head>

<body>
     <header>
        <div class="container header-container">
            <h1 class="logo">MEDICARE</h1>
            <nav class="nav-links">
                <a href="index.php" class="active">Accueil</a>
                <a href="tout_parcourir.php">Tout Parcourir</a>
                <a href="rechercher.php">Recherche</a>
                <a href="mes_rendezvous.php">Rendez-Vous</a>
                <nav>
                    <?php if (isset($_SESSION['role'])): ?>
                        <a href="<?php
                            if ($_SESSION['role'] === 'admin') {
                                echo 'admin_dashboard.php';
                            } elseif ($_SESSION['role'] === 'client') {
                                echo 'client_dashboard.php';
                            } elseif ($_SESSION['role'] === 'medecin') {
                                echo 'medecin_dashboard.php';
                            }
                        ?>">
                            <button class="btn-login">Mon profil</button>
                        </a>
                        <a href="logout.php"><button class="btn-login">Se déconnecter</button></a>
                    <?php else: ?>
                        <a href="client_login.php"><button class="btn-login">Connexion Client</button></a>
                        <a href="medecin_login.php"><button class="btn-login">Connexion Médecin</button></a>
                    <?php endif; ?>
                </nav>
            </nav>
        </div>
    </header>

<div class="container-svg">
    <div class="svg-zone">
        <svg id="body" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 198.81 693.96"><title>SVG Human Test</title><path class="hand-right" d="M22.25,348.54c0,5.26,2.87,6.53,5.42,11.47S27,375.94,27,375.94c2.23,14.82.48,14.82-2.23,14.66s-6.53-12.75-6.53-12.75l-4-1.91s-3.19,3.51-.8,8.92,16.25,12.75,14.66,14.66-6.37-.16-6.37-0.16,9.56,9.24,8.45,10.36a3.53,3.53,0,0,1-2.87.8s2.71,3.19.48,4.62-8.6-3.19-8.6-3.19C10.46,410.69,2,389.33.74,386.94s2.07-30.28,3-40.64Z" transform="translate(0.5 0.5)"/><path class="forearm-right" d="M43.76,195.54c-2.39,3.19-4.94,16.09-5.1,25.82s-3.19,23.27-5.74,29,2.23,35.22-.32,50.36-10.36,42.55-10.36,47.81L3.76,346.3c1-10.36-5.42-86.06-3.35-90.68s4-15.46,2.71-22.63S0.42,189.49,4.4,179.92s-0.8-27.25,9.88-44.62,35.06-16.73,35.06-16.73Z" transform="translate(0.5 0.5)"/><path class="hand-left" d="M175.56,348.54c0,5.26-2.87,6.53-5.42,11.47s0.64,15.94.64,15.94c-2.23,14.82-.48,14.82,2.23,14.66s6.53-12.75,6.53-12.75l4-1.91s3.19,3.51.8,8.92-16.25,12.75-14.66,14.66,6.37-.16,6.37-0.16-9.56,9.24-8.45,10.36a3.53,3.53,0,0,0,2.87.8s-2.71,3.19-.48,4.62,8.61-3.19,8.61-3.19c8.76-1.28,17.21-22.63,18.49-25s-2.07-30.28-3-40.64Z" transform="translate(0.5 0.5)"/><path class="forearm-left" d="M194,346.3c-1-10.36,5.42-86.06,3.35-90.68s-4-15.46-2.71-22.63,2.71-43.51-1.27-53.07,0.8-27.25-9.88-44.62-35.06-16.73-35.06-16.73l5.58,77c2.39,3.19,4.94,16.09,5.1,25.82s3.19,23.27,5.74,29-2.23,35.22.32,50.36,10.36,42.55,10.36,47.81Z" transform="translate(0.5 0.5)"/><path class="thigh-right" d="M35.11,508.33c1.67-14.63,4.15-24,4.67-31.36,0.8-11.31-5.26-89.88-4.46-111.07L97,368.29s0.32,12.43-2.07,21-7.33,19-7.33,33.78-2.23,48.45-6.53,62.31c-3.08,9.94-7,16-7.48,22.91H35.11Z" transform="translate(0.5 0.5)"/><path class="calf-right" d="M52.37,640.8c0.48-6.53-4.14-41.75-8.76-55.3s-10-16.25-10-48.76a248.59,248.59,0,0,1,1.54-28.4H73.57a21.52,21.52,0,0,0,1.27,8.8c4,11.47,4.62,37.45.48,54.5s-1.75,52.27-1.44,55.3Z" transform="translate(0.5 0.5)"/><path class="foot-right" d="M73.88,626.94c0.32,3,3.35,6.05,4.94,12.91s-3.51,9.56-1.75,20.4,2.55,31.56-3.35,32.51S66.39,691,66.39,691c-5.9.48-22.79,0.16-25.66-3.19s6.85-26.93,7.81-30.28,0.8-6.69.91-9.4,2.92-7.33,2.92-7.33Z" transform="translate(0.5 0.5)"/><path class="head" d="M122.33,106.46c-3.19-4.62-1.59-24.7-1.59-24.7,6.21-4.62,6.85-18.17,6.85-18.17s0.48,2.39,2.87.8,3.19-17.69,2.55-19.12-3.35-1-3.35-1,3.82-21-2.71-31.87S105,0,98.9,0s-21.51,1.59-28,12.43S68.15,44.3,68.15,44.3s-2.71-.48-3.35,1S65,62.79,67.35,64.38s2.87-.8,2.87-0.8,0.64,13.55,6.85,18.17c0,0,1.59,20.08-1.59,24.7h46.85Z" transform="translate(0.5 0.5)"/><path class="torso-lower" d="M50.73,247.36a136.19,136.19,0,0,1-3.62,28.82c-2.55,10.36-11,68.53-11.79,89.72L97,368.29l1.91-.82,1.91,0.82,61.67-2.39c-0.8-21.2-9.24-79.36-11.79-89.72a136.21,136.21,0,0,1-3.62-28.82H50.73Z" transform="translate(0.5 0.5)"/><path class="torso-upper" d="M147.08,247.36c-0.06-7.1.64-14.06,2.71-19.47,5.31-13.86,1.87-35.54,4.26-32.35l-5.58-77s-22.95-7.49-26.13-12.11H75.48c-3.19,4.62-26.13,12.11-26.13,12.11l-5.58,77C46.15,192.36,42.71,214,48,227.9c2.07,5.41,2.78,12.37,2.71,19.47h96.34Z" transform="translate(0.5 0.5)"/><path class="calf-left" d="M145.44,640.8c-0.48-6.53,4.14-41.75,8.76-55.3s10-16.25,10-48.76a248.59,248.59,0,0,0-1.54-28.4H124.24a21.52,21.52,0,0,1-1.27,8.8c-4,11.47-4.62,37.45-.48,54.5s1.75,52.27,1.44,55.3Z" transform="translate(0.5 0.5)"/><path class="foot-left" d="M123.93,626.94c-0.32,3-3.35,6.05-4.94,12.91s3.51,9.56,1.75,20.4-2.55,31.56,3.35,32.51,7.33-1.75,7.33-1.75c5.9,0.48,22.79.16,25.66-3.19s-6.85-26.93-7.81-30.28-0.8-6.69-.91-9.4-2.92-7.33-2.92-7.33Z" transform="translate(0.5 0.5)"/><path class="thigh-left" d="M162.7,508.33c-1.67-14.63-4.15-24-4.67-31.36-0.8-11.31,5.26-89.88,4.46-111.07l-61.67,2.39s-0.32,12.43,2.07,21,7.33,19,7.33,33.78,2.23,48.45,6.53,62.31c3.08,9.94,7,16,7.48,22.91H162.7Z" transform="translate(0.5 0.5)"/><path class="outline" d="M0.42,255.62C2.49,251,4.4,240.17,3.13,233S0.42,189.49,4.4,179.92s-0.8-27.25,9.88-44.62,35.06-16.73,35.06-16.73,22.95-7.49,26.13-12.11,1.59-24.7,1.59-24.7c-6.21-4.62-6.85-18.17-6.85-18.17s-0.48,2.39-2.87.8S64.16,46.69,64.8,45.26s3.35-1,3.35-1-3.82-21,2.71-31.87S92.85,0,98.9,0s21.51,1.59,28,12.43,2.71,31.87,2.71,31.87,2.71-.48,3.35,1-0.16,17.53-2.55,19.12-2.87-.8-2.87-0.8S127,77.13,120.74,81.75c0,0-1.59,20.08,1.59,24.7s26.13,12.11,26.13,12.11,24.39-.63,35.06,16.73,5.9,35.06,9.88,44.62,2.55,45.9,1.27,53.07,0.64,18,2.71,22.63-4.3,80.32-3.35,90.68,4.3,38.25,3,40.64-9.72,23.75-18.49,25c0,0-6.37,4.62-8.61,3.19s0.48-4.62.48-4.62a3.53,3.53,0,0,1-2.87-.8c-1.12-1.11,8.45-10.36,8.45-10.36s-4.78,2.07-6.37.16,12.27-9.24,14.66-14.66-0.8-8.92-.8-8.92l-4,1.91s-3.83,12.59-6.53,12.75-4.46.16-2.23-14.66c0,0-3.19-11-.64-15.94s5.42-6.21,5.42-11.47-7.81-32.67-10.36-47.81,2.23-44.62-.32-50.36-5.58-19.29-5.74-29-2.71-22.63-5.1-25.82,1.05,18.49-4.26,32.35-1.64,37.93.91,48.29,11,68.53,11.79,89.72S157.23,465.66,158,477s6.21,27.25,6.21,59.76-5.42,35.22-10,48.76-9.24,48.76-8.76,55.3c0,0,2.81,4.62,2.92,7.33s0,6.06.91,9.4,10.68,26.93,7.81,30.28-19.76,3.67-25.66,3.19c0,0-1.43,2.71-7.33,1.75s-5.1-21.68-3.35-32.51-3.35-13.54-1.75-20.4,4.62-9.88,4.94-12.91,2.71-38.25-1.44-55.3-3.51-43,.48-54.5-1.91-17.85-6.22-31.71-6.53-47.49-6.53-62.31-4.94-25.18-7.33-33.78-2.07-21-2.07-21l-1.91-.82-1.91.82s0.32,12.43-2.07,21-7.33,19-7.33,33.78-2.23,48.45-6.53,62.31-10.2,20.24-6.22,31.71,4.62,37.45.48,54.5-1.75,52.27-1.44,55.3,3.35,6.05,4.94,12.91-3.51,9.56-1.75,20.4,2.55,31.56-3.35,32.51S66.39,691,66.39,691c-5.9.48-22.79,0.16-25.66-3.19s6.85-26.93,7.81-30.28,0.8-6.69.91-9.4,2.92-7.33,2.92-7.33c0.48-6.53-4.14-41.75-8.76-55.3s-10-16.25-10-48.76S39,488.29,39.78,477,34.52,387.1,35.32,365.9s9.24-79.36,11.79-89.72,6.22-34.42.91-48.29-1.87-35.54-4.26-32.35-4.94,16.09-5.1,25.82-3.19,23.27-5.74,29,2.23,35.22-.32,50.36-10.36,42.55-10.36,47.81,2.87,6.53,5.42,11.47S27,375.94,27,375.94c2.23,14.82.48,14.82-2.23,14.66s-6.53-12.75-6.53-12.75l-4-1.91s-3.19,3.51-.8,8.92,16.25,12.75,14.66,14.66-6.37-.16-6.37-0.16,9.56,9.24,8.45,10.36a3.53,3.53,0,0,1-2.87.8s2.71,3.19.48,4.62-8.6-3.19-8.6-3.19C10.46,410.69,2,389.33.74,386.94s2.07-30.28,3-40.64S-1.66,260.24.42,255.62Z" transform="translate(0.5 0.5)"/></svg>
        
    </div>
    <div class="result-zone" id="resultats">
        <p>Sélectionne une zone du corps pour afficher les médecins spécialisés.</p>
    </div>
</div>

<script>
    const paths = document.querySelectorAll('svg path');
    paths.forEach(part => {
        part.addEventListener('click', function () {
            document.querySelectorAll('.active-zone').forEach(el => el.classList.remove('active-zone'));
            this.classList.add('active-zone');
            const zone = this.classList[0];
            document.getElementById('resultats').innerHTML = "<p>Chargement...</p>";
            fetch(`?zone=${zone}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('resultats').innerHTML = data;
                })
                .catch(error => {
                    document.getElementById('resultats').innerHTML = "Erreur de chargement.";
                    console.error("Erreur AJAX :", error);
                });
        });
    });
</script>
</body>
</html>
