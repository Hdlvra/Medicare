<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt_admin = $conn->prepare("SELECT * FROM client WHERE email = ? AND role = 'admin'");
    $stmt_admin->bind_param("s", $email);
    $stmt_admin->execute();
    $res_admin = $stmt_admin->get_result();

    if ($res_admin->num_rows === 1) {
        $admin = $res_admin->fetch_assoc();
        if ($mot_de_passe === $admin['mot_de_passe']) {
            $_SESSION['id'] = $admin['id'];
            $_SESSION['role'] = 'admin';
            header("Location: admin_dashboard.php");
            exit();
        }
    }

    $stmt = $conn->prepare("SELECT * FROM medecin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $medecin = $res->fetch_assoc();
        if ($mot_de_passe === $medecin['mot_de_passe']) {
            $_SESSION['medecin_id'] = $medecin['id'];
            $_SESSION['role'] = 'medecin';
            header("Location: medecin_dashboard.php");
            exit();
        } else {
            $erreur = "Mot de passe incorrect.";
        }
    } else {
        $erreur = "Email non trouvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion - MediCare</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .login-container {
      max-width: 400px;
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
<body>
    <div class="login-container">
        <h2>Connexion Médecin</h2>
        <?php if (isset($erreur)) echo "<p style='color:red;'>$erreur</p>"; ?>
        <form method="post" action="">
            <label>Email :</label>
            <input type="email" name="email" id="email" required>
            <label>Mot de passe :</label>
            <input type="password" name="mot_de_passe" id="password" required><br><br>
            <button type="submit">Se connecter</button>
        </form>

        <p><a href="index.html">Retour à l'accueil</a></p>
    </div>
</body>
</html>
