<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'client') {
    header("Location: client_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Laboratoire non spécifié.";
    exit();
}

$id_labo = intval($_GET['id']);

// Récupération des infos labo
$stmt = $conn->prepare("SELECT nom FROM laboratoire WHERE id = ?");
$stmt->bind_param("i", $id_labo);
$stmt->execute();
$result = $stmt->get_result();
$labo = $result->fetch_assoc();

if (!$labo) {
    echo "Laboratoire introuvable.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Prendre rendez-vous avec <?= htmlspecialchars($labo['nom']) ?> - MediCare</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Marriweather', sans-serif;
            background-color: #f7f9fa;
            color: #2c3e50;
            padding: 2rem;
        }

        h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: #34495e;
        }

        form {
            background-color: #ffffff;
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 0.6rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 0.5rem;
        }

        button[type="submit"] {
            margin-top: 2rem;
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            display: block;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #27ae60;
        }

        .btn-retour {
            display: block;
            max-width: 600px;
            text-align: center;
            margin: 2rem auto 0;
            text-decoration: none;
            color: white;
            background-color: #3498db;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
        }

        .btn-retour:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>

  

    <h2>Prendre rendez-vous avec <?= htmlspecialchars($labo['nom']) ?></h2>

    <form method="post" action="tout_parcourir.php">
        <input type="hidden" name="id_labo" value="<?= $id_labo ?>">

        <label for="jour">Choisissez un jour :</label>
        <select name="jour" id="jour" required>
            <option value="">-- Choisissez un jour --</option>
            <option value="1">Lundi</option>
            <option value="2">Mardi</option>
            <option value="3">Mercredi</option>
            <option value="4">Jeudi</option>
            <option value="5">Vendredi</option>
            <option value="6">Samedi</option>
        </select>

        <label for="date">Choisissez une date :</label>
        <select name="date" id="date" required>
            <option value="">-- Choisissez une date --</option>
        </select>

        <label for="creneau">Choisissez un créneau horaire :</label>
        <select name="creneau" id="creneau" required>
            <option value="">-- Choisissez un créneau --</option>
        </select>

        <button type="submit">Valider le rendez-vous</button>
    </form>

    <a href="tout_parcourir.php" class="btn-retour">Retour</a>

    <script>
        const idLabo = <?= $id_labo ?>;

        document.getElementById('jour').addEventListener('change', function () {
            const jour = this.value;

            fetch('get_dates.php?jour=' + jour)
                .then(res => res.json())
                .then(data => {
                    const dateSelect = document.getElementById('date');
                    dateSelect.innerHTML = '<option value="">-- Choisissez une date --</option>';
                    data.forEach(d => {
                        const option = document.createElement('option');
                        option.value = d;
                        option.textContent = d;
                        dateSelect.appendChild(option);
                    });

                    document.getElementById('creneau').innerHTML = '<option value="">-- Choisissez un créneau --</option>';
                });
        });

        document.getElementById('date').addEventListener('change', function () {
            const date = this.value;

            fetch(`get_creneaux_labo.php?id_labo=${idLabo}&date=${date}`)
                .then(res => res.json())
                .then(data => {
                    const creneauSelect = document.getElementById('creneau');
                    creneauSelect.innerHTML = '<option value="">-- Choisissez un créneau --</option>';
                    data.forEach(creneau => {
                        const option = document.createElement('option');
                        option.value = creneau;
                        option.textContent = creneau;
                        creneauSelect.appendChild(option);
                    });
                });
        });
    </script>
</body>
</html>
