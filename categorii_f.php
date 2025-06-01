<?php
// categorii_f.php
session_start();
include 'config/db.php';

$serviciu = $_GET['serviciu'] ?? '';

if (!$serviciu) {
    echo "Serviciu lipsă!";
    exit();
}

// Interogare furnizori după tip serviciu
$stmt = $conn->prepare("SELECT id, nume, email, telefon FROM furnizor WHERE serviciu = ?");
$stmt->bind_param("s", $serviciu);
$stmt->execute();
$result = $stmt->get_result();
?>




<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Furnizori pentru <?= htmlspecialchars($serviciu) ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .furnizor {
            border: 1px solid #ccc;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h1>Furnizori pentru: <?= htmlspecialchars($serviciu) ?></h1>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="furnizor">';
            echo '<strong>' . htmlspecialchars($row['nume']) . '</strong><br>';
            echo 'Email: ' . htmlspecialchars($row['email']) . '<br>';
            echo 'Telefon: ' . htmlspecialchars($row['telefon']) . '<br><br>';

            // Buton către profilul furnizorului
            echo '<form method="get" action="read_only_f.php" style="margin-top: 10px;">';
            echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
            echo '<button type="submit" style="padding: 6px 12px; background-color: #007bff; color: white; border: none; border-radius: 4px;">Vezi profil</button>';
            echo '</form>';

            echo '</div>';

        }
    } else {
        echo "<p>Nu există furnizori pentru acest serviciu.</p>";
    }
    ?>
</body>
</html>
