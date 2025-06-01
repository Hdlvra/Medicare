<?php
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Erreur : " . $conn->connect_error);
}

$results_medecin = [];
$results_labo = [];

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['query'])) {
    $search = "%" . $_GET['query'] . "%";

 
    $stmt1 = $conn->prepare("SELECT * FROM medecin WHERE nom LIKE ? OR specialite LIKE ? OR bureau LIKE ?");
    $stmt1->bind_param("sss", $search, $search, $search);
    $stmt1->execute();
    $results_medecin = $stmt1->get_result();

    
    $stmt2 = $conn->prepare("SELECT * FROM laboratoire WHERE nom LIKE ? OR adresse LIKE ? OR horaires LIKE ?");
    $stmt2->bind_param("sss", $search, $search, $search);
    $stmt2->execute();
    $results_labo = $stmt2->get_result();
}

session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche - Medicare</title>
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

        .search-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 2rem;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        .search-container input[type="text"] {
            width: 75%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-container button {
            padding: 0.55rem 1rem;
            background-color: #0077cc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .results {
            margin-top: 20px;
        }

        .result-item {
            padding: 1rem;
            border-bottom: 1px solid #ddd;
            background-color: white;
            border-radius: 6px;
            margin-bottom: 10px;
            transition: background-color 0.2s ease;
        }

        .result-item:hover {
            background-color: #eef6fb;
        }

        .result-item a {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .result-item strong {
            color: #0077cc;
        }
    </style>
</head>
<body>
<header>
        <div class="container header-container">
            <h1 class="logo">MEDICARE</h1>
            <nav class="nav-links">
                <a href="index.php" >Accueil</a>
                <a href="tout_parcourir.php" >Tout Parcourir</a>
                <a href="rechercher.php" class="active">Recherche</a>
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

<div class="search-container">
    <h2>Rechercher un Médecin ou un Laboratoire</h2>
    <form method="get">
        <input type="text" name="query" placeholder="Nom, Spécialité, Adresse..." required>
        <button type="submit">Rechercher</button>
    </form>

    <div class="results">
        <?php if (!empty($_GET['query'])): ?>
            <h3>Résultats pour « <?= htmlspecialchars($_GET['query']) ?> »</h3>

            <?php if ($results_medecin->num_rows > 0): ?>
                <h4>Médecins :</h4>
                <?php while($row = $results_medecin->fetch_assoc()): ?>
                    <div class="result-item">
                        <a href="fiche_medecin.php?id=<?= $row['id'] ?>">
                            <strong><?= htmlspecialchars($row['prenom']) ?> <?= htmlspecialchars($row['nom']) ?></strong><br>
                            Spécialité : <?= htmlspecialchars($row['specialite']) ?><br>
                            Bureau : <?= htmlspecialchars($row['bureau']) ?><br>
                            Email : <?= htmlspecialchars($row['email']) ?>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

            <?php if ($results_labo->num_rows > 0): ?>
                <h4>Laboratoires :</h4>
                <?php while($labo = $results_labo->fetch_assoc()): ?>
                    <div class="result-item">
                        <a href="fiche_laboratoire.php?id=<?= $labo['id'] ?>">
                            <strong><?= htmlspecialchars($labo['nom']) ?></strong><br>
                            Adresse : <?= htmlspecialchars($labo['adresse']) ?><br>
                            Téléphone : <?= htmlspecialchars($labo['telephone']) ?><br>
                            Email : <?= htmlspecialchars($labo['email']) ?>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

            <?php if ($results_medecin->num_rows === 0 && $results_labo->num_rows === 0): ?>
                <p>Aucun résultat trouvé pour « <?= htmlspecialchars($_GET['query']) ?> »</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
