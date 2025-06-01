<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérifier que le médecin est connecté
if (!isset($_SESSION['medecin_id']) || $_SESSION['role'] !== 'medecin') {
    header("Location: medecin_login.php");
    exit();
}

// Vérifier que l'ID du patient est bien passé
if (!isset($_GET['id'])) {
    echo "Patient non spécifié.";
    exit();
}

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM client WHERE id = $id");

if ($res->num_rows !== 1) {
    echo "Client introuvable.";
    exit();
}

$patient = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Patient - MediCare</title>
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
        <h2><?= htmlspecialchars($patient['prenom'] . ' ' . $patient['nom']) ?></h2>

        <p><strong>Email :</strong> <?= htmlspecialchars($patient['email']) ?></p>
        <p><strong>Carte Vitale :</strong> <?= htmlspecialchars($patient['carte_vitale'] ?? 'Non renseignée') ?></p>
        <p><strong>Adresse :</strong> <?= htmlspecialchars($patient['adresse'] ?? 'Non renseignée') ?></p>
        <p><strong>Moyen de paiement :</strong> <?= htmlspecialchars($patient['moyen_paiement'] ?? 'Non renseigné') ?></p>

        <a class="btn-retour" href="javascript:history.back()">← Retour à la discussion</a>
    </div>
</body>
</html>
