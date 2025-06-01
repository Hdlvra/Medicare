<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'client') {
    echo "Non autorisé.";
    exit();
}

if (isset($_POST['id_labo'], $_POST['date'], $_POST['creneau'])) {
    $id_client = $_SESSION['id'];
    $id_labo = intval($_POST['id_labo']);
    $date = $_POST['date'];
    $heure_debut = $_POST['creneau'];
    
  
    $timestamp_debut = strtotime($heure_debut);
    $heure_fin = date("H:i:s", $timestamp_debut + 30 * 60);

   
    $stmt = $conn->prepare("SELECT 1 FROM rdv_labo WHERE id_laboratoire = ? AND date = ? AND heure_debut = ?");
    $stmt->bind_param("iss", $id_labo, $date, $heure_debut);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
     
        $insert = $conn->prepare("INSERT INTO rdv_labo (id_client, id_laboratoire, date, heure_debut, heure_fin) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("iisss", $id_client, $id_labo, $date, $heure_debut, $heure_fin);

        if ($insert->execute()) {
            echo "Rendez-vous confirmé avec succès.";
        } else {
            echo "Erreur lors de l'insertion : " . $insert->error;
        }
    } else {
        echo "Ce créneau est déjà réservé.";
    }
} else {
    echo "Données manquantes.";
}
?>
