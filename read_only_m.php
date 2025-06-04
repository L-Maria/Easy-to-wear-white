<?php
session_start();
include 'config\db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Nu este identificat niciun utilizator.";
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID-ul clientului nu a fost transmis.";
    exit();
}

$client_id=intval($_GET['id']);

// Caută oferta acceptată pentru acest client și extrage furnizorul
$servicii_bifate = [];

$stmt_servicii = $conn->prepare("
    SELECT f.serviciu, f.nume 
    FROM notificari n
    JOIN furnizor f ON n.furnizor_id = f.id
    WHERE n.client_id = ? AND n.oferta_acceptata = 1
");
$stmt_servicii->bind_param("i", $client_id);
$stmt_servicii->execute();
$result = $stmt_servicii->get_result();

while ($row = $result->fetch_assoc()) {
    $serviciu = strtolower(trim($row['serviciu']));
    $servicii_bifate[$serviciu] = $row['nume'];
}

$stmt_servicii->close();



// Verifică dacă oferta a fost acceptată pentru acest client
$stmt_oferta = $conn->prepare("SELECT COUNT(*) FROM notificari WHERE client_id = ? AND oferta_acceptata = 1");
$stmt_oferta->bind_param("i", $client_id);
$stmt_oferta->execute();
$stmt_oferta->bind_result($oferta_acceptata);
$stmt_oferta->fetch();
$stmt_oferta->close();


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
$stmt_user->bind_param("i", $client_id);
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
<header><?= htmlspecialchars($user_data['nume']) ?></header>
<main>
    <div class="panel-stanga">
        <h3>Profilul clientului</h3>
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
    
    $serviciu_key = strtolower(trim($task));
    $checked = isset($servicii_bifate[$serviciu_key]) ? 'checked' : '';
    
    if ($checked) {
        echo '<span style="margin-right: 10px; font-style: italic; color: #333;">' . htmlspecialchars($servicii_bifate[$serviciu_key]) . '</span>';
    }

    echo '<input type="checkbox" disabled ' . $checked . '>';
    echo '</div>';
}

        ?>
    </div>
</main>
</body>
</html>
