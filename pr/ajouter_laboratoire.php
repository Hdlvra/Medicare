<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $salle = $_POST['salle'];
    $photo = $_POST['photo'];
    $adresse = $_POST['adresse'];
    $horaires = $_POST['horaires'];

    $stmt = $conn->prepare("INSERT INTO laboratoire (nom, email, telephone, salle, photo, adresse, horaires)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nom, $email, $telephone, $salle, $photo, $adresse, $horaires);

    if ($stmt->execute()) {
        $message = "Laboratoire ajouté avec succès.";
    } else {
        $erreur = "Erreur : " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajout Laboratoire - MediCare</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .login-container {
      max-width: 500px;
      margin: 80px auto;
      padding: 2rem;
      border: 1px solid #ddd;
      border-radius: 10px;
      background-color: #ffffff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .login-container h2 {
      text-align: center;
      color: #0077cc;
      margin-bottom: 1.5rem;
    }

    .login-container label {
      display: block;
      margin-bottom: 0.5rem;
      color: #333;
    }

    .login-container input {
      width: 100%;
      padding: 0.5rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .login-container button {
      width: 100%;
      padding: 0.6rem;
      background-color: #0077cc;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
    }

    .login-container button:hover {
      background-color: #005fa3;
    }

    .login-container p {
      text-align: center;
      margin-top: 1rem;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Ajouter un laboratoire</h2>
    <?php if (isset($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>
    <?php if (isset($message)) echo "<p style='color:green;'>$message</p>"; ?>

    <form method="post" action="">
      <label>Nom :</label>
      <input type="text" name="nom" required>

      <label>Email :</label>
      <input type="email" name="email" required>

      <label>Téléphone :</label>
      <input type="text" name="telephone" required>

      <label>Salle :</label>
      <input type="text" name="salle" required>

      <label>Chemin photo (ex: labo/labo1.jpg) :</label>
      <input type="text" name="photo" required>

      <label>Adresse :</label>
      <input type="text" name="adresse" required>

      <label>Horaires :</label>
      <input type="text" name="horaires" required>

      <button type="submit">Ajouter le laboratoire</button>
    </form>

    <p><a href="admin_dashboard.php">Retour au tableau de bord</a></p>
  </div>
</body>
</html>
