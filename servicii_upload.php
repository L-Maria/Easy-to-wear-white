<?php
session_start(); // foarte important să ai sesiunea pornită

include 'config/db.php'; // conexiune la baza de date

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {
    $furnizor_id = $_SESSION['user_id'] ?? null;

    if (!$furnizor_id) {
        die("Utilizatorul nu este autentificat.");
    }

    $uploadDir = 'uploads/';

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $filename = time() . '_' . basename($_FILES['images']['name'][$key]);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($tmp_name, $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO images (path, furnizor_id) VALUES (?, ?)");
            $stmt->bind_param("si", $targetFile, $furnizor_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: home_f.php");
    exit();
}
?>
