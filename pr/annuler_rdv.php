<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Vérifier que le client est connecté
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'client') {
    header("Location: client_login.php");
    exit();
}

if (isset($_POST['rdv_id'])) {
    $rdv_id = $_POST['rdv_id'];
    $stmt = $conn->prepare("DELETE FROM rdv WHERE id = ?");
    $stmt->bind_param("i", $rdv_id);
    $stmt->execute();
}

header('Location: mes_rendezvous.php');
exit;
?>