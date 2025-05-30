<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupération des médecins
$result = $conn->query("SELECT * FROM medecin");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Médecins - MediCare</title>
    <style>
        body {
  font-family: 'Marriweather', sans-serif;
  background-color: #fff;
  color: #111;
  line-height: 1.6;
}

.container {
  width: 90%;
  max-width: 1200px;
  margin: auto;
}
        header {
        padding: 1rem 0;
        border-bottom: 1px solid #eaeaea;
        }

        .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        }

        .logo {
        color: #0077B6;
        }

        .nav-links {
        font-family: 'Lato';
        display: flex;
        gap: 1.5rem;
        align-items: center;
        }

        .nav-links a {
        text-decoration: none;
        color: #333;
        font-weight: 500;
        }

        .nav-links a.active {
        color: #0077B6;
        }

        .btn-login {
        padding: 0.5rem 1rem;
        background-color: #000;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        }

        h1.medecin-title {
            text-align: center;
            color: #0077cc;
            margin: 2rem 0;
        }

        .medecin-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 2rem;
        }

        .medecin-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            width: 300px;
            padding: 1rem;
            text-align: center;
            transition: transform 0.2s;
        }

        .medecin-card:hover {
            transform: translateY(-5px);
        }

        .medecin-photo {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }

        .medecin-name {
            margin: 0.5rem 0 0.2rem;
            color: #0077cc;
        }

        .medecin-info {
            margin: 0.2rem 0;
            font-size: 0.9rem;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-container">
            <h1 class="logo">MEDICARE</h1>
            <nav class="nav-links">
                <a href="index.html" class="active">Accueil</a>
                <a href="#">Tout Parcourir</a>
                <a href="#">Recherche</a>
                <a href="#">Rendez-Vous</a>
            </nav>
        </div>
    </header>
    <div class="medecin-container">
        <?php while ($medecin = $result->fetch_assoc()) { ?>
            <div class="medecin-card">
                <img class="medecin-photo" src="<?php echo $medecin['photo']; ?>" alt="Photo de <?php echo $medecin['nom']; ?>">
                <h3 class="medecin-name"><?php echo $medecin['prenom'] . " " . $medecin['nom']; ?></h3>
                <p class="medecin-info"><strong>Email :</strong> <?php echo $medecin['email']; ?></p>
                <p class="medecin-info"><strong>Spécialité :</strong> <?php echo $medecin['specialite']; ?></p>
                <p class="medecin-info"><strong>Bureau :</strong> <?php echo $medecin['bureau']; ?></p>
                <p class="medecin-info"><strong>Disponibilité :</strong> <?php echo $medecin['disponibilite']; ?></p>
            </div>
        <?php } ?>
    </div>
</body>
</html>