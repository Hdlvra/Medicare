<?php
session_start();


if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'client') {
    header("Location: client_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$id_client = $_SESSION['id'];

$sql = "SELECT * FROM client WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_client);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Client introuvable.";
    exit();
}

$client = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte - MediCare</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .account-container {
            max-width: 600px;
            margin: 80px auto;
            padding: 2rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .account-container h2 {
            text-align: center;
            color: #0077cc;
            margin-bottom: 1.5rem;
        }

        .account-container p {
            font-size: 1rem;
            margin: 0.5rem 0;
            color: #333;
        }

        .account-container a {
            display: block;
            text-align: center;
            margin-top: 2rem;
            color: #0077cc;
            text-decoration: none;
        }

        .account-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="account-container">
        <h2>Mon Compte</h2>

        <?php foreach ($client as $champ => $valeur): ?>
            <p><strong><?= ucfirst(htmlspecialchars($champ)) ?> :</strong> <?= htmlspecialchars($valeur) ?></p>
        <?php endforeach; ?>

        <a href="index.php">← Retour au tableau de bord</a>
    </div>
</body>
</html>
