<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if (!isset($_SESSION['medecin_id']) || $_SESSION['role'] !== 'medecin') {
    header("Location: medecin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("UPDATE rdv SET statut = 'confirme' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        http_response_code(200);
    } else {
        http_response_code(400); 
    }

    $stmt->close();
} else {
    http_response_code(400); 
}
?>