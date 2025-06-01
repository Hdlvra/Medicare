<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if (!isset($_SESSION['id']) && !isset($_SESSION['medecin_id'])) {
    header('Location: index.php');
    exit;
}

$type = $_SESSION['role']; 
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tout Parcourir - MediCare</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            font-family: 'Marriweather', sans-serif;
            background-color: #fff;
            color: #111;
            line-height: 1.6;
        }

        h2 {
            margin-top: 2rem;
            color: #333;
        }

        .rdv-section {
            margin-bottom: 3rem;
        }

        h2 {
            color: #333;
            margin-bottom: 1rem;
        }

        .rdv-list {
            margin-top: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            justify-content: center;
        }

        .rdv-card {
            margin-top: 1rem;
            position: relative;
            width: 280px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
            padding: 1rem 1.5rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: inherit;
            transition: box-shadow 0.3s ease;
        }

        .rdv-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .medecin-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-top: -40px;
            border: 4px solid white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            background-color: #f0f0f0;
        }

        .statut-dot {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
        }

        .dot-confirme {
            background-color: #27ae60;
        }

        .dot-en_attente {
            background-color: #f1c40f;
        }

        .rdv-info {
            text-align: center;
            margin-top: 1rem;
            color: #2d3436;
        }

        .rdv-info strong {
            color: #636e72;
        }

        .rdv-date {
            margin-top: 1rem;
            font-weight: bold;
            color: #34495e;
            font-size: 1.1rem;
            background-color: #ecf0f1;
            padding: 0.4rem 1rem;
            border-radius: 10px;
        }

        .btn-annuler {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            cursor: pointer;
        }

        .btn-confirmer {
            background-color: rgb(36, 141, 71);
            color: white;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            cursor: pointer;
        }

        .btn-retour {
            background-color: #3498db;
            color: white;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 2rem;
        }

        .btn-retour:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
     <header>
        <div class="container header-container">
            <h1 class="logo">MEDICARE</h1>
            <nav class="nav-links">
                <a href="index.php" >Accueil</a>
                <a href="tout_parcourir.php">Tout Parcourir</a>
                <a href="rechercher.php">Recherche</a>
                <a href="mes_rendezvous.php"class="active">Rendez-Vous</a>
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



    <?php if ($type == 'client') : ?>
        <?php
        $id = $_SESSION['id'];
        $stmt = $conn->prepare("
        SELECT r.id, r.date_rdv, r.heure_rdv, r.statut, r.id_medecin,
               m.nom, m.prenom, m.specialite, m.bureau, m.photo
        FROM rdv r
        JOIN medecin m ON r.id_medecin = m.id
        WHERE r.id_client = ?
        ORDER BY r.date_rdv, r.heure_rdv
    ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $rdvs = ['confirme' => [], 'en_attente' => []];
        while ($row = $res->fetch_assoc()) {
            $rdvs[$row['statut']][] = $row;
        }

        $stmt_labo = $conn->prepare("
        SELECT rl.id, rl.date, rl.heure_debut, l.nom, l.photo
        FROM rdv_labo rl
        JOIN laboratoire l ON rl.id_laboratoire = l.id
        WHERE rl.id_client = ?
        ORDER BY rl.date, rl.heure_debut
    ");
        $stmt_labo->bind_param("i", $id);
        $stmt_labo->execute();
        $res_labo = $stmt_labo->get_result();

        $rdvs_labo = [];
        while ($row = $res_labo->fetch_assoc()) {
            $rdvs_labo[] = $row;
        }

        foreach (['confirme', 'en_attente'] as $statut) : ?>
            <div class="rdv-section">
                <center>
                <h2>
                    Rendez-vous <?= $statut === 'confirme' ? 'confirmés' : 'en attente de confirmation' ?>
                </h2>
        </center>
                <div class="rdv-list">
                    <?php if (empty($rdvs[$statut])) : ?>
                        <p>Aucun rendez-vous <?= $statut ?>.</p>
                    <?php else : ?>
                        <?php foreach ($rdvs[$statut] as $rdv) : ?>
                            <a href="fiche_medecin.php?id=<?= $rdv['id_medecin'] ?>" class="rdv-card">
                                <!-- Pastille de statut -->
                                <div class="statut-dot dot-<?= $statut ?>"></div>

                                <!-- Photo médecin -->
                                <img src="<?= $rdv['photo']; ?>" alt="Photo médecin" class="medecin-photo">

                                <!-- Infos -->
                                <div class="rdv-info"><strong>Dr <?= htmlspecialchars($rdv['prenom'] . ' ' . $rdv['nom']) ?></strong></div>
                                <div class="rdv-info"><?= htmlspecialchars($rdv['specialite']) ?> | <?= htmlspecialchars($rdv['bureau']) ?></div>

                                <!-- Date/heure -->
                                <div class="rdv-date">
                                    <?= date("d/m/Y", strtotime($rdv['date_rdv'])) ?> à <?= date("H:i", strtotime($rdv['heure_rdv'])) ?>
                                </div>

                                <!-- Bouton annuler (si attente) -->
                                <?php if ($statut === 'en_attente') : ?>
                                    <button class="btn-annuler" onclick="annulerRdv(event, <?= $rdv['id'] ?>)">Annuler</button>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                </div>
            <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <center>
        <h2>Rendez-vous en laboratoire</h2>
                                </center>
        <div class="rdv-list">
            <?php if (empty($rdvs_labo)) : ?>
                <p>Aucun rendez-vous laboratoire.</p>
            <?php else : ?>
                <?php foreach ($rdvs_labo as $rdv) : ?>
                    <a href="fiche_laboratoire.php?id=<?= $rdv['id'] ?>" class="rdv-card">

                        <div class="statut-dot dot-confirme"></div>

                        <!-- Image laboratoire -->
                        <img src="<?= htmlspecialchars($rdv['photo']) ?>" alt="Image laboratoire" class="medecin-photo">

                        <!-- Infos -->
                        <div class="rdv-info"><strong><?= htmlspecialchars($rdv['nom']) ?></strong></div>
                        <div class="rdv-info">Laboratoire</div>

                        <!-- Date/heure -->
                        <div class="rdv-date">
                            <?= date("d/m/Y", strtotime($rdv['date'])) ?> à <?= date("H:i", strtotime($rdv['heure_debut'])) ?>
                        </div>

                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($type == 'medecin') : ?>
        <?php
        $id = $_SESSION['medecin_id'];
        $stmt = $conn->prepare("
        SELECT r.id, r.date_rdv, r.heure_rdv, r.statut, r.id_client, r.id_medecin,
               c.nom, c.prenom, c.email,
               m.specialite, m.bureau
        FROM rdv r
        JOIN client c ON r.id_client = c.id
        JOIN medecin m ON r.id_medecin = m.id
        WHERE r.id_medecin = ?
        AND r.date_rdv >= CURDATE()
        ORDER BY r.date_rdv, r.heure_rdv
    ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $rdvs = ['confirme' => [], 'en_attente' => []];
        while ($row = $res->fetch_assoc()) {
            $rdvs[$row['statut']][] = $row;
        }

        foreach (['confirme', 'en_attente'] as $statut) : ?>
            <div class="rdv-section">
                <h2 style="text-align: center;">
                    Rendez-vous <?= $statut === 'confirme' ? 'confirmés' : 'en attente de confirmation' ?>
                </h2>
                <div class="rdv-list">
                    <?php if (empty($rdvs[$statut])) : ?>
                        <p>Aucun rendez-vous <?= $statut === 'confirme' ? 'confirmés' : 'en attente de confirmation' ?>.</p>
                    <?php else : ?>
                        <?php foreach ($rdvs[$statut] as $rdv) : ?>
                            <a href="fiche_patient.php?id=<?= $rdv['id_client'] ?>" class="rdv-card">
                                <!-- Pastille de statut -->
                                <div class="statut-dot dot-<?= $statut ?>"></div>

                                <!-- Infos -->
                                <div class="rdv-info"><strong> <?= htmlspecialchars($rdv['prenom'] . ' ' . $rdv['nom']) ?></strong></div>
                                <div class="rdv-info"><?= htmlspecialchars($rdv['specialite']) ?> | <?= htmlspecialchars($rdv['bureau']) ?></div>

                                <!-- Date/heure -->
                                <div class="rdv-date">
                                    <?= date("d/m/Y", strtotime($rdv['date_rdv'])) ?> à <?= date("H:i", strtotime($rdv['heure_rdv'])) ?>
                                </div>

                                <!-- Bouton annuler (si attente) -->
                                <?php if ($statut === 'en_attente') : ?>
                                    <button class="btn-annuler" onclick="annulerRdv(event, <?= $rdv['id'] ?>)">Annuler</button>
                                    <button class="btn-confirmer" onclick="confirmerRdv(event, <?= $rdv['id'] ?>)">Confirmer</button>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                </div>
            <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

<script>
    function annulerRdv(event, idRdv) {
        event.stopPropagation(); 
        event.preventDefault(); 

        if (confirm("Voulez-vous vraiment annuler ce rendez-vous ?")) {
            fetch('annuler_rdv.php?id=' + idRdv, {
                    method: 'GET'
                })
                .then(response => {
                    if (response.ok) {
                        alert("Rendez-vous annulé.");
                        window.location.reload();
                    } else {
                        alert("Erreur lors de l'annulation.");
                    }
                });
        }
    }

    function confirmerRdv(event, idRdv) {
        event.stopPropagation(); 
        event.preventDefault();

        if (confirm("Voulez-vous vraiment confirmer ce rendez-vous ?")) {
            fetch('confirmer_rdv.php?id=' + idRdv, {
                    method: 'GET'
                })
                .then(response => {
                    if (response.ok) {
                        alert("Rendez-vous confirmé.");
                        window.location.reload();
                    } else {
                        alert("Erreur lors de la confirmation.");
                    }
                });
        }
    }
</script>

</html>