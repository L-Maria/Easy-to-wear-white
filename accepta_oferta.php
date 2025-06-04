<?php
session_start();
include 'config\db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notificare_id'])) {
    $notificare_id = intval($_POST['notificare_id']);

    // Marchează oferta ca acceptată
    $stmt = $conn->prepare("UPDATE notificari SET oferta_acceptata = 1 WHERE id = ?");
    $stmt->bind_param("i", $notificare_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: notificari.php");
exit();
