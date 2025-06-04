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
    <link rel="stylesheet" href="categorii_f.css?v=1.3">
</head>
<body>
    <header>Furnizori pentru: <?= htmlspecialchars($serviciu) ?></header>
    <div class="furnizori-wrapper">;
    <?php
    if ($result->num_rows > 0) {
         while ($row = $result->fetch_assoc()): ?>
            <article class="furnizor-card">
            <div class="furnizor-content">
                <div class="furnizor-info">
                    <h2><?= htmlspecialchars($row['nume']) ?></h2>
                    <p>Email: <?= htmlspecialchars($row['email']) ?></p>
                    <p>Telefon: <?= htmlspecialchars($row['telefon']) ?></p>
                </div>
                <form method="get" action="read_only_f.php">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                    <button type="submit" class="go-to">Vezi profil</button>
                </form>
            </div>
            </article>
        <?php endwhile; 

        } else {
        echo "<p>Nu există furnizori pentru acest serviciu.</p>";
        }
    ?>
</body>
</html>
