<?php
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) { die("Erreur : " . $conn->connect_error); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $conn->prepare("INSERT INTO client (nom, prenom, email, mot_de_passe, adresse, role) VALUES (?, ?, ?, ?, ?, 'client')");
    $stmt->bind_param("sssss", $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['mot_de_passe'], $_POST['adresse']);
    if ($stmt->execute()) {
        $message = "Compte créé.";
    } else {
        $erreur = "Erreur : " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8"><title>Inscription</title></head>
<body>
<h2>Créer un compte client</h2>
<?php if (isset($message)) echo "<p>$message</p>"; if (isset($erreur)) echo "<p>$erreur</p>"; ?>
<form method="post">
    <label>Nom :</label><input name="nom" required><br>
    <label>Prénom :</label><input name="prenom" required><br>
    <label>Email :</label><input type="email" name="email" required><br>
    <label>Mot de passe :</label><input type="password" name="mot_de_passe" required><br>
    <label>Adresse :</label><input name="adresse" required><br>
    <button type="submit">S'inscrire</button>
</form>
<p><a href="client_login.php">Retour</a></p>
</body>
</html>