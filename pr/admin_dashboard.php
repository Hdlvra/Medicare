<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérification de l'accès admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Récupération des statistiques
$nb_clients = $conn->query("SELECT COUNT(*) as total FROM client")->fetch_assoc()['total'];
$nb_medecins = $conn->query("SELECT COUNT(*) as total FROM medecin")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Administrateur - MediCare</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            max-width: 900px;
            margin: 60px auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #0077cc;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin-top: 2rem;
        }

        .stat-card {
            background: #f0f0f0;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            width: 40%;
        }

        .stat-card h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }

        .add-btn {
            display: block;
            margin: 1rem auto;
            background-color: #0077cc;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            text-align: center;
            text-decoration: none;
        }

        .add-btn:hover {
            background-color: #005fa3;
        }

        .logout-btn {
            background-color: #999;
        }

        .logout-btn:hover {
            background-color: #777;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Tableau de bord - Administrateur</h1>

        <div class="stats">
            <div class="stat-card">
                <h2>Utilisateurs</h2>
                <p><?= $nb_clients ?></p>
            </div>
            <div class="stat-card">
                <h2>Médecins</h2>
                <p><?= $nb_medecins ?></p>
            </div>
        </div>

        <a class="add-btn" href="ajouter_medecin.php">Ajouter un médecin</a>
        <a class="add-btn" href="ajouter_laboratoire.php">Ajouter un laboratoire</a>
        <a class="add-btn logout-btn" href="logout.php">Se déconnecter</a>
    </div>
</body>
</html>
