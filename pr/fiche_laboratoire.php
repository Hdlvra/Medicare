<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    echo "Laboratoire non spécifié.";
    exit;
}

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM laboratoire WHERE id = $id");

if ($res->num_rows !== 1) {
    echo "Laboratoire introuvable.";
    exit;
}

$labo = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Laboratoire - MediCare</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .fiche-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 2rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .fiche-container h2 {
            text-align: center;
            color: #0077cc;
            margin-bottom: 1.5rem;
        }

        .fiche-container img {
            display: block;
            margin: 0 auto 1rem;
            width: 200px;
            height: auto;
            border-radius: 8px;
            object-fit: cover;
        }

        .fiche-container p {
            font-size: 1rem;
            margin: 0.5rem 0;
            color: #333;
        }

        .fiche-container a {
            color: #0077cc;
            text-decoration: none;
        }

        .fiche-container a:hover {
            text-decoration: underline;
        }

        .fiche-container .btn-retour {
            display: block;
            text-align: center;
            margin-top: 2rem;
            color: #0077cc;
            text-decoration: none;
        }

        .fiche-container .btn-retour:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="fiche-container">
        <h2><?= htmlspecialchars($labo['nom']) ?></h2>

        <?php if (!empty($labo['image'])): ?>
            <img src="<?= htmlspecialchars($labo['image']) ?>" alt="Image du laboratoire">
        <?php endif; ?>

        <p><strong>Adresse :</strong> <?= htmlspecialchars($labo['adresse']) ?></p>
        <p><strong>Téléphone :</strong> <?= htmlspecialchars($labo['telephone']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($labo['email']) ?></p>
        <p><strong>Horaires :</strong> <?= htmlspecialchars($labo['horaires']) ?></p>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="contact_labo.php?id=<?= $labo['id'] ?>">
                <button style="padding: 0.7rem 1.5rem; background-color: #0077cc; color: white; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer;">
                    Contacter ce laboratoire
                </button>
            </a>
        </div>

        <a class="btn-retour" href="tout_parcourir.php">← Retour à la liste</a>
    </div>
</body>
</html>
