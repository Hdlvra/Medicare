<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if (!isset($_SESSION['medecin_id']) || $_SESSION['role'] !== 'medecin') {
    header("Location: medecin_login.php");
    exit();
}

$medecin_id = intval($_SESSION['medecin_id']);
$res = $conn->query("SELECT * FROM medecin WHERE id = $medecin_id");
$medecin = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Médecin - MediCare</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            max-width: 700px;
            margin: 80px auto;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .dashboard-container h2 {
            color: #0077cc;
            margin-bottom: 1rem;
        }

        .dashboard-container p {
            font-size: 1rem;
            color: #333;
            margin-bottom: 2rem;
        }

        .dashboard-button {
            display: block;
            width: 80%;
            margin: 1rem auto;
            padding: 1rem;
            font-size: 1.1rem;
            background-color: #0077cc;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .dashboard-button:hover {
            background-color: #005fa3;
        }

        .logout-button {
            background-color: #999;
        }

        .logout-button:hover {
            background-color: #777;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Tableau de bord du Médecin</h2>
        <p>Bienvenue Dr <?= htmlspecialchars($medecin['prenom'] . " " . $medecin['nom']) ?></p>

        <a href="mes_rendezvous.php" class="dashboard-button"> Voir mes rendez-vous</a>
        <a href="medecin_chats.php" class="dashboard-button"> Voir mes chats en cours</a>

        <a href="logout.php" class="dashboard-button logout-button"> Se déconnecter</a>
    </div>
</body>
</html>
