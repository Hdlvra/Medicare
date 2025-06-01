<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $specialite = $_POST['specialite'];
    $photo = $_POST['photo'];
    $bureau = $_POST['bureau'];
    $disponibilite = $_POST['disponibilite'];
    $cv = $_POST['cv'];

    $stmt = $conn->prepare("INSERT INTO medecin (nom, prenom, email, mot_de_passe, specialite, photo, bureau, disponibilite, cv)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $nom, $prenom, $email, $mot_de_passe, $specialite, $photo, $bureau, $disponibilite, $cv);

    if ($stmt->execute()) {
        $message = " Médecin ajouté avec succès.";
    } else {
        $erreur = " Erreur : " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajout Médecin - MediCare</title>
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

    .login-container input, .login-container select {
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
      transition: background-color 0.3s ease;
    }

    .login-container button:hover {
      background-color: #005fa3;
    }

    .login-container p {
      text-align: center;
      margin-top: 1rem;
      font-size: 0.9rem;
    }

    .login-container a {
      color: #0077cc;
      text-decoration: none;
    }

    .login-container a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Ajouter un médecin</h2>
    <?php if (isset($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>
    <?php if (isset($message)) echo "<p style='color:green;'>$message</p>"; ?>

    <form method="post" action="">
      <label>Nom :</label>
      <input type="text" name="nom" required>

      <label>Prénom :</label>
      <input type="text" name="prenom" required>

      <label>Email :</label>
      <input type="email" name="email" required>

      <label>Mot de passe :</label>
      <input type="text" name="mot_de_passe" required>

      <label>Spécialité :</label>
      <select name="specialite" required>
        <option value="generaliste">Généraliste</option>
        <option value="addictologie">Addictologie</option>
        <option value="andrologie">Andrologie</option>
        <option value="cardiologie">Cardiologie</option>
        <option value="dermatologie">Dermatologie</option>
        <option value="gastro">Gastro-entérologie</option>
        <option value="gynécologie">Gynécologie</option>
        <option value="ist">IST</option>
        <option value="ostéopathie">Ostéopathie</option>
      </select>

      <label>Chemin photo (ex: img/nom.jpg) :</label>
      <input type="text" name="photo" required>

      <label>Bureau :</label>
      <input type="text" name="bureau" required>

      <label>Disponibilité :</label>
      <input type="text" name="disponibilite" required>

      <label>Chemin CV (ex: cv/nom.xml) :</label>
      <input type="text" name="cv" required>

      <button type="submit">Ajouter le médecin</button>
    </form>

    <p><a href="admin_dashboard.php">Retour au tableau de bord</a></p>
  </div>
</body>
</html>
