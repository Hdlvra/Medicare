<?php
session_start();
$conn = new mysqli("localhost", "root", "", "medicare");
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}


if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'client') {
    http_response_code(403);
    exit();
}

if (isset($_GET['id'])) {
    $rdv_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM rdv WHERE id = ?");
    $stmt->bind_param("i", $rdv_id);
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