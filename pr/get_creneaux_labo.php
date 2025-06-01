<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}


if (!isset($_GET['id_labo']) || !isset($_GET['date'])) {
    echo json_encode([]);
    exit;
}

$id_labo = intval($_GET['id_labo']);
$date = $_GET['date'];


$timestamp = strtotime($date);
$jour_semaine = date("w", $timestamp);
if ($jour_semaine == 0) {
    echo json_encode([]); 
    exit;
}

$stmt = $conn->prepare("SELECT heure_debut, heure_fin FROM disponibilites_labo WHERE id_laboratoire = ?");
$stmt->bind_param("i", $id_labo);
$stmt->execute();
$stmt->bind_result($heure_debut, $heure_fin);

if (!$stmt->fetch()) {
    echo json_encode([]); 
    exit;
}
$stmt->close();

$creneaux = [];
$start = strtotime($heure_debut);
$end = strtotime($heure_fin);

while ($start < $end) {
    $creneau = date("H:i", $start);
    $creneaux[] = $creneau;
    $start += 30 * 60; 
}


$stmt = $conn->prepare("SELECT heure_debut FROM rdv_labo WHERE id_laboratoire = ? AND date = ?");
$stmt->bind_param("is", $id_labo, $date);
$stmt->execute();
$result = $stmt->get_result();

$creneaux_pris = [];
while ($row = $result->fetch_assoc()) {
    $creneaux_pris[] = substr($row['heure_debut'], 0, 5); 
$stmt->close();


$creneaux_disponibles = array_values(array_diff($creneaux, $creneaux_pris));

echo json_encode($creneaux_disponibles);
