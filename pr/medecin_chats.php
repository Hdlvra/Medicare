<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérification de la session médecin
if (!isset($_SESSION['medecin_id']) || $_SESSION['role'] !== 'medecin') {
    header("Location: medecin_login.php");
    exit();
}

$medecin_id = intval($_SESSION['medecin_id']);

// Récupérer les derniers messages par client
$sql = "
    SELECT c.id AS client_id, c.nom, c.prenom, m.contenu, m.date_envoi
    FROM message m
    JOIN client c ON m.id_client = c.id
    WHERE m.id_medecin = ?
      AND m.date_envoi = (
          SELECT MAX(m2.date_envoi)
          FROM message m2
          WHERE m2.id_client = m.id_client AND m2.id_medecin = m.id_medecin
      )
    ORDER BY m.date_envoi DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $medecin_id);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chats en cours - Médecin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 60px auto;
            background-color: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #0077cc;
            margin-bottom: 1.5rem;
        }

        .chat-entry {
            border-bottom: 1px solid #eee;
            padding: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .chat-entry:hover {
            background-color: #f1f1f1;
        }

        .chat-name {
            font-weight: bold;
            color: #0077cc;
        }

        .chat-message {
            margin: 0.3rem 0;
            color: #333;
        }

        .chat-time {
            font-size: 0.85rem;
            color: #888;
        }

        .btn-retour {
            display: block;
            text-align: center;
            margin-top: 2rem;
        }

        .btn-retour a {
            background-color: #0077cc;
            padding: 0.7rem 1.5rem;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-retour a:hover {
            background-color: #005fa3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Chats en cours avec les patients</h2>

    <?php if ($res->num_rows > 0): ?>
        <?php while ($row = $res->fetch_assoc()): ?>
            <div class="chat-entry" onclick="window.location.href='conversation_medecin.php?id=<?= $row['client_id'] ?>'">
                <div class="chat-name"><?= htmlspecialchars($row['prenom'] . ' ' . $row['nom']) ?></div>
                <div class="chat-message"><?= htmlspecialchars($row['contenu']) ?></div>
                <div class="chat-time"><?= date("d/m/Y H:i", strtotime($row['date_envoi'])) ?></div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; color:#888;">Aucune conversation trouvée.</p>
    <?php endif; ?>

    <div class="btn-retour">
        <a href="medecin_dashboard.php">← Retour au tableau de bord</a>
    </div>
</div>
</body>
</html>
