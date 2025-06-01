<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    echo "Médecin non spécifié.";
    exit;
}

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM medecin WHERE id = $id");

if ($res->num_rows !== 1) {
    echo "Médecin introuvable.";
    exit;
}

$med = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Médecin - MediCare</title>
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
        <h2>Dr <?= htmlspecialchars($med['prenom'] . ' ' . $med['nom']) ?></h2>
        <?php if (!empty($med['photo'])): ?>
            <img src="<?= htmlspecialchars($med['photo']) ?>" alt="Photo du médecin">
        <?php endif; ?>

        <p><strong>Email :</strong> <?= htmlspecialchars($med['email']) ?></p>
        <p><strong>Spécialité :</strong> <?= htmlspecialchars($med['specialite']) ?></p>
        <p><strong>Bureau :</strong> <?= htmlspecialchars($med['bureau']) ?></p>
        <p><strong>Disponibilités :</strong><br>
            <?php
            $stmt = $conn->prepare("SELECT jour_semaine, heure_debut, heure_fin FROM disponibilites WHERE id_medecin = ? ORDER BY FIELD(jour_semaine, 'Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'), heure_debut");
            $stmt->bind_param("i", $med['id']);
            $stmt->execute();
            $dispos = $stmt->get_result();

            $jours = [];
            while ($row = $dispos->fetch_assoc()) {
                $jour = $row['jour_semaine'];
                $heures = date('H:i', strtotime($row['heure_debut'])) . ' - ' . date('H:i', strtotime($row['heure_fin']));
                $jours[$jour][] = $heures;
            }

            if (empty($jours)) {
                echo "Non renseigné";
            } else {
                foreach ($jours as $jour => $heures) {
                    echo "<strong>$jour :</strong> " . implode(", ", $heures) . "<br>";
                }
            }
            ?>
        </p>
        <p><strong>CV :</strong>
            <?php if (!empty($med['cv'])): ?>
                <a href="<?= htmlspecialchars($med['cv']) ?>" target="_blank">Voir le CV</a>
            <?php else: ?>
                Non fourni
            <?php endif; ?>
        </p>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="chat.php?id=<?= $med['id'] ?>">
                <button style="padding: 0.7rem 1.5rem; background-color: #0077cc; color: white; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer;">
                    Lancer un chat avec ce médecin
                </button>
            </a>
            <a href="prendre_rdv.php?id=<?= $med['id'] ?>">
                <button style="padding: 0.7rem 1.5rem; background-color: #0077cc; color: white; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer; margin-right: 1rem;">
                    Prendre un rendez-vous
                </button>
            </a>
        </div>

        <a class="btn-retour" href="tout_parcourir.php">← Retour à la liste</a>
    </div>
</body>
</html>
