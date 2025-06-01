<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérifie si l'utilisateur est connecté comme médecin
if (!isset($_SESSION['medecin_id']) || $_SESSION['role'] !== 'medecin') {
    header("Location: medecin_login.php");
    exit();
}

if (isset($_POST['rdv_id']) && isset($_POST['action'])) {
    $rdv_id = $_POST['rdv_id'];
    $action = $_POST['action'];

    if ($action === 'confirmer') {
        $stmt = $conn->prepare("UPDATE rdv SET statut = 'confirme' WHERE id = ?");
    } elseif ($action === 'annuler') {
        $stmt = $conn->prepare("DELETE FROM rdv WHERE id = ?");
    }

    $stmt->bind_param("i", $rdv_id);
    $stmt->execute();
}

header('Location: mes_rendezvous.php');
exit;
?>