<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupérer les médecins
$result = $conn->query("SELECT * FROM medecin");

// Récupérer les laboratoires
$laboratoires = $conn->query("SELECT * FROM laboratoire");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tout Parcourir - MediCare</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        

        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
        }

        .btn-login {
            padding: 0.5rem 1rem;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        h1.section-title {
            text-align: center;
            color: #0077cc;
            margin: 2rem 0 1rem;
        }

        .card-container, .medecin-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 2rem;
        }

        .card, .medecin-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            width: 300px;
            padding: 1rem;
            text-align: center;
            transition: transform 0.2s;
            text-decoration: none;
            color: inherit;
        }

        .card:hover, .medecin-card:hover {
            transform: translateY(-5px);
        }

        .card-photo, .medecin-photo {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }

        .card-title, .medecin-name {
            margin: 0.5rem 0 0.2rem;
            color: #0077cc;
        }

        .card-info, .medecin-info {
            margin: 0.2rem 0;
            font-size: 0.9rem;
            color: #333;
        }

        .medecin-container a {
    text-decoration: none;
    color: inherit;
}
    </style>
</head>
<body>
<header>
    <div class="container header-container">
        <h1 class="logo">MEDICARE</h1>
        <nav class="nav-links">
            <a href="index.php">Accueil</a>
            <a href="tout_parcourir.php" class="active">Tout Parcourir</a>
            <a href="rechercher.php">Recherche</a>
            <a href="mes_rendezvous.php">Rendez-Vous</a>

            <?php if (isset($_SESSION['role'])): ?>
                <a href="<?php
                    if ($_SESSION['role'] === 'admin') echo 'admin_dashboard.php';
                    elseif ($_SESSION['role'] === 'client') echo 'client_dashboard.php';
                    elseif ($_SESSION['role'] === 'medecin') echo 'medecin_dashboard.php';
                ?>">
                    <button class="btn-login">Mon profil</button></a>
                <a href="logout.php"><button class="btn-login">Se déconnecter</button></a>
            <?php else: ?>
                <a href="client_login.php"><button class="btn-login">Connexion Client</button></a>
                <a href="medecin_login.php"><button class="btn-login">Connexion Médecin</button></a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<!-- Section Médecins -->
<h1 class="section-title">Médecins disponibles</h1>
<div class="medecin-container">
    <?php while ($medecin = $result->fetch_assoc()) { ?>
	<a href="fiche_medecin.php?id=<?= $medecin['id'] ?>" >
        <div class="medecin-card">
            <img class="medecin-photo" src="<?= $medecin['photo']; ?>" alt="Photo de <?= $medecin['nom']; ?>">
            <h3 class="medecin-name"><?= $medecin['prenom'] . " " . $medecin['nom']; ?></h3>
            <p class="medecin-info"><strong>Email :</strong> <?= $medecin['email']; ?></p>
            <p class="medecin-info"><strong>Spécialité :</strong> <?= $medecin['specialite']; ?></p>
            <p class="medecin-info"><strong>Bureau :</strong> <?= $medecin['bureau']; ?></p>
            <p class="medecin-info"><strong>Disponibilités :</strong><br>
                <?php
                $id = $medecin['id'];
                $stmt = $conn->prepare("SELECT jour_semaine, heure_debut, heure_fin FROM disponibilites WHERE id_medecin = ? ORDER BY FIELD(jour_semaine, 'Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'), heure_debut");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $dispos = $stmt->get_result();

                $jours = [];
                while ($row = $dispos->fetch_assoc()) {
                    $jour = $row['jour_semaine'];
                    $heures = date('H:i', strtotime($row['heure_debut'])) . ' - ' . date('H:i', strtotime($row['heure_fin']));
                    $jours[$jour][] = $heures;
                }

                foreach ($jours as $jour => $heures) {
                    echo "<strong>$jour :</strong> " . implode(", ", $heures) . "<br>";
                }
                ?>
            </p>
        </div>
        </a>
    <?php } ?>
</div>

<!-- Section Laboratoires -->
<h1 class="section-title">Laboratoires de biologie médicale</h1>
<div class="card-container">
    <?php while ($lab = $laboratoires->fetch_assoc()) { ?>
        <a href="fiche_laboratoire.php?id=<?= $lab['id'] ?>" class="card">
            <img class="card-photo" src="<?= $lab['photo']; ?>" alt="Photo du laboratoire <?= $lab['nom']; ?>">
            <h3 class="card-title"><?= $lab['nom']; ?></h3>
            <p class="card-info"><strong>Salle :</strong> <?= $lab['salle']; ?></p>
            <p class="card-info"><strong>Email :</strong> <?= $lab['email']; ?></p>
            <p class="card-info"><strong>Téléphone :</strong> <?= $lab['telephone']; ?></p>
        </a>
    <?php } ?>
</div>

</body>
</html>
