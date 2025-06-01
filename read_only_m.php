<?php
session_start();
include 'config\db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Nu este identificat niciun utilizator.";
    exit();
}

$host = 'localhost';
$db = 'wedding_app';
$user = 'root';
$pass = '';
$user_id = $_SESSION['user_id'];

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}

// Încarcă datele utilizatorului
$stmt_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows !== 1) {
    echo "Utilizatorul nu a fost găsit.";
    exit();
}

$user_data = $result_user->fetch_assoc();
$stmt_user->close();

// Citește detaliile existente
$stmt = $conn->prepare("SELECT detalii FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($detaliiExistente);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Home - Nunta Eleganta</title>
    <link rel="stylesheet" href="home_m.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
<header>Home</header>
<main>
    <div class="panel-stanga">
        <h3>Profilul tău</h3>
        <p><strong>Nume:</strong> <?= htmlspecialchars($user_data['nume']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user_data['email']) ?></p>
        <p><strong>Telefon:</strong> <?= htmlspecialchars($user_data['telefon']) ?></p>
        <p><strong>Religie:</strong> <?= htmlspecialchars($user_data['religie']) ?></p>
        <p><strong>Data nuntă:</strong> <?= htmlspecialchars($user_data['data_nunta']) ?></p>
        <p><strong>Locație:</strong> <?= htmlspecialchars($user_data['locatie']) ?></p>
        <p><strong>Nr invitați:</strong> <?= htmlspecialchars($user_data['invitati']) ?></p>

        <div style="max-width: 500px; margin-top: 20px;">
            <label style="font-weight: bold;">Îmi doresc...</label>
            <div style="white-space: pre-wrap; padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                <?= htmlspecialchars($detaliiExistente ?? '') ?>
            </div>
        </div>
    </div>

    <div class="todo">
        <h2>To Do List</h2>
        <?php
        $taskuri = [
            "Locatie",
            "Invitatii",
            "Decor",
            "Formatie",
            "Foto/Video",
            "Gustari",
            "Atelier",
            "Altele"
        ];

        foreach ($taskuri as $task) {
            echo '<div class="task">';
            echo '<a href="categorii_f.php?serviciu=' . urlencode($task) . '" target="_blank">';
            echo '<button>' . htmlspecialchars($task) . '</button>';
            echo '</a>';
            echo '<input type="checkbox">';
            echo '</div>';
        }
        ?>
    </div>
</main>
</body>
</html>
