<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}


if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'client') {
    header("Location: client_login.php");
    exit();
}


$id_client = $_SESSION['id'];
$id_medecin = isset($_GET['id']) ? intval($_GET['id']) : 0;


$medecin = $conn->query("SELECT * FROM medecin WHERE id = $id_medecin")->fetch_assoc();
if (!$medecin) {
    echo "Médecin introuvable.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['message'])) {
    $contenu = $conn->real_escape_string($_POST['message']);
    $conn->query("INSERT INTO message (id_client, id_medecin, expediteur, contenu) 
                  VALUES ($id_client, $id_medecin, 'client', '$contenu')");
}

$msgs = $conn->query("SELECT * FROM message 
                      WHERE id_client = $id_client AND id_medecin = $id_medecin 
                      ORDER BY date_envoi ASC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Discussion avec Dr <?= htmlspecialchars($medecin['nom']) ?></title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f5f5f5;
    }

    .chat-container {
      max-width: 700px;
      margin: 50px auto;
      padding: 1.5rem;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #0077cc;
    }

    .messages {
      height: 400px;
      overflow-y: auto;
      border: 1px solid #ddd;
      padding: 1rem;
      border-radius: 8px;
      background-color: #fafafa;
      margin-bottom: 1rem;
    }

    .message {
      margin-bottom: 0.8rem;
      padding: 0.6rem;
      border-radius: 8px;
      max-width: 80%;
      position: relative;
    }

    .client {
      background-color: #d1ecf1;
      align-self: flex-end;
      text-align: right;
      margin-left: auto;
    }

    .medecin {
      background-color: #f8d7da;
      align-self: flex-start;
    }

    .timestamp {
      font-size: 0.8rem;
      color: #666;
      margin-top: 0.3rem;
      display: block;
    }

    form {
      display: flex;
      gap: 10px;
    }

    input[type="text"] {
      flex: 1;
      padding: 0.6rem;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      padding: 0.6rem 1.2rem;
      background-color: #0077cc;
      border: none;
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #005fa3;
    }
  </style>
</head>
<body>
  <div class="chat-container">
    <h2>Discussion avec Dr <?= htmlspecialchars($medecin['prenom'] . " " . $medecin['nom']) ?></h2>

    <div class="messages" id="chatBox">
      <?php while ($msg = $msgs->fetch_assoc()): ?>
        <div class="message <?= $msg['expediteur'] === 'client' ? 'client' : 'medecin' ?>">
          <?= nl2br(htmlspecialchars($msg['contenu'])) ?>
          <span class="timestamp"><?= date("H:i", strtotime($msg['date_envoi'])) ?></span>
        </div>
      <?php endwhile; ?>
    </div>

    <form method="post">
      <input type="text" name="message" placeholder="Votre message..." required>
      <button type="submit">Envoyer</button>
    </form>
    <div style="text-align:center; margin-top: 20px;">
    <a href="index.php">
        <button type="button" style="padding: 0.5rem 1.5rem; background-color: #333; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Retour à l'accueil
        </button>
    </a>
</div>
  </div>

  <script>
    // Scroll automatique en bas au chargement
    const chatBox = document.getElementById("chatBox");
    chatBox.scrollTop = chatBox.scrollHeight;
  </script>
</body>
</html>
