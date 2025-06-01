<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérifier que le client est connecté
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'client') {
    header("Location: client_login.php");
    exit();
}

// Récupération des IDs
$id_client = $_SESSION['id'];
$id_medecin = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer les jours de disponibilité du médecin
$dispo_query = $conn->prepare("SELECT DISTINCT jour_semaine FROM disponibilites WHERE id_medecin = ?");
$dispo_query->bind_param("i", $id_medecin);
$dispo_query->execute();
$jours_result = $dispo_query->get_result();
$jours = [];
while ($row = $jours_result->fetch_assoc()) {
    $jours[] = $row['jour_semaine'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Prendre Rendez-vous - MediCare</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            font-family: 'Marriweather', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #0077cc;
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-top: 1rem;
            color: #333;
        }
        select, button {
            width: 100%;
            padding: 0.8rem;
            margin-top: 0.5rem;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }
        button {
            background-color: #0077cc;
            color: white;
            border: none;
            margin-top: 1.5rem;
            cursor: pointer;
        }
        button:hover {
            background-color: #005fa3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Prendre rendez-vous</h2>
    <form method="post" action="enregistrer_rdv.php">
        <input type="hidden" name="id_medecin" value="<?= $id_medecin ?>">

        <label for="jour">Jour :</label>
        <select name="jour" id="jour" required>
            <option value="">-- Choisissez un jour --</option>
            <?php foreach ($jours as $jour): ?>
                <option value="<?= $jour ?>"><?= $jour ?></option>
            <?php endforeach; ?>
        </select>

        <label for="date">Date :</label>
        <select name="date" id="date" required>
            <option value="">-- Choisissez une date --</option>
        </select>

        <label for="creneau">Créneau :</label>
        <select name="creneau" id="creneau" required>
            <option value="">-- Choisissez un créneau --</option>
        </select>

        <button type="submit">Réserver</button>
    </form>
</div>

<script>
document.getElementById('jour').addEventListener('change', function () {
    const jour = this.value;
    const medecinId = <?= $id_medecin ?>;

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
        });
});

document.getElementById('date').addEventListener('change', function () {
    const date = this.value;
    const jour = document.getElementById('jour').value;

    fetch(`get_creneaux.php?id_medecin=<?= $id_medecin ?>&jour=${jour}&date=${date}`)
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
 