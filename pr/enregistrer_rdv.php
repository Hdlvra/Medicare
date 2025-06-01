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
$id_medecin = $_POST['id_medecin'];
$date = $_POST['date'];
$heure = $_POST['creneau'];

$stmt = $conn->prepare("INSERT INTO rdv (id_client, id_medecin, date_rdv, heure_rdv, statut) VALUES (?, ?, ?, ?, 'en_attente')");
$stmt->bind_param("iiss", $id_client, $id_medecin, $date, $heure);

if ($stmt->execute()) {
    echo "Rendez-vous demandé avec succès !";
} else {
    echo "Erreur lors de la demande : " . $conn->error;
}

header("Location: fiche_medecin.php?id=$id_medecin&success=1");
?>