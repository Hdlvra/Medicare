<?php
$conn = new mysqli("localhost", "root", "", "medicare");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if (!isset($_GET['id_medecin'], $_GET['jour'], $_GET['date'])) {
    die('Paramètres manquants');
}

$id_medecin = $_GET['id_medecin'];
$jour = $_GET['jour'];
$date = $_GET['date'];

$stmt = $conn->prepare("SELECT heure_debut, heure_fin FROM disponibilites WHERE id_medecin = ? AND jour_semaine = ?");
$stmt->bind_param("is", $id_medecin, $jour);
$stmt->execute();
$res = $stmt->get_result();

$creneaux = [];

while ($row = $res->fetch_assoc()) {
    $start = new DateTime($row['heure_debut']);
    $end = new DateTime($row['heure_fin']);

    while ($start < $end) {
        $slot = $start->format('H:i');
        
        // vérifier s'il est déjà pris
        $check = $conn->prepare("SELECT 1 FROM rdv WHERE id_medecin = ? AND date_rdv = ? AND heure_rdv = ? AND statut IN ('en_attente', 'confirme')");
        $check->bind_param("iss", $id_medecin, $date, $slot);
        $check->execute();
        $check->store_result();

        if ($check->num_rows === 0) {
            $creneaux[] = $slot;
        }

        $start->modify('+30 minutes');
    }
}

echo json_encode($creneaux);
?>