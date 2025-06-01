<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifier que le client est connecté
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'client') {
    header("Location: client_login.php");
    exit();
}

$id_client = $_SESSION['id'];
$id_labo = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Vérifier que le laboratoire existe
$labo = $conn->query("SELECT * FROM laboratoire WHERE id = $id_labo")->fetch_assoc();
if (!$labo) {
    echo "Laboratoire introuvable.";
    exit();
}

// Envoi de message
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['message'])) {
    $contenu = $conn->real_escape_string($_POST['message']);
    $conn->query("INSERT INTO message_labo (id_client, id_laboratoire, expediteur, contenu) 
                  VALUES ($id_client, $id_labo, 'client', '$contenu')");
}

// Récupération des messages
$msgs = $conn->query("SELECT * FROM message_labo 
                      WHERE id_client = $id_client AND id_laboratoire = $id_labo 
                      ORDER BY date_envoi ASC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Discussion avec <?= htmlspecialchars($labo['nom']) ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
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
    }
    .client {
      background-color: #d1ecf1;
      text-align: right;
      margin-left: auto;
    }
    .labo {
      background-color: #f8d7da;
      text-align: left;
    }
    .timestamp {
      font-size: 0.8rem;
      color: #666;
      margin-top: 0.3rem;
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
    <h2>Discussion avec <?= htmlspecialchars($labo['nom']) ?></h2>
    <div class="messages" id="chatBox">
      <?php while ($msg = $msgs->fetch_assoc()): ?>
        <div class="message <?= $msg['expediteur'] === 'client' ? 'client' : 'labo' ?>">
          <?= nl2br(htmlspecialchars($msg['contenu'])) ?>
          <div class="timestamp"><?= date("d/m/Y H:i", strtotime($msg['date_envoi'])) ?></div>
        </div>
      <?php endwhile; ?>
    </div>

    <form method="post">
      <input type="text" name="message" placeholder="Votre message..." required>
      <button type="submit">Envoyer</button>
    </form>

    <div style="text-align:center; margin-top: 20px;">
      <a href="index.php">
        <button type="button" style="padding: 0.5rem 1.5rem;">Retour à l'accueil</button>
      </a>
    </div>
  </div>

  <script>
    const chatBox = document.getElementById("chatBox");
    chatBox.scrollTop = chatBox.scrollHeight;
  </script>
</body>
</html>
