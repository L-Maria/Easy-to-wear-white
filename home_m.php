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

// Ia serviciile furnizorilor care au acceptat oferta pentru clientul curent
$servicii_acceptate = [];

$stmt = $conn->prepare("
    SELECT f.serviciu, f.nume
    FROM notificari n 
    JOIN furnizor f ON n.furnizor_id = f.id 
    WHERE n.client_id = ? AND n.oferta_acceptata = 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $serviciu = strtolower(trim($row['serviciu']));
    $nume_furnizor = $row['nume'];
    $servicii_acceptate[$serviciu] = $nume_furnizor;
}

$stmt->close();


// Salvează detalii dacă formularul a fost trimis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['detalii'])) {
    $detalii = trim($conn->real_escape_string($_POST['detalii']));
    $stmt = $conn->prepare("UPDATE users SET detalii = ? WHERE id = ?");
    $stmt->bind_param("si", $detalii, $user_id);
    $stmt->execute();
    $stmt->close();
}

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
    <!-- Fonturi Google -->
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

        <form method="post" style="max-width: 500px; margin-top: 20px;">
            <label for="detalii" style="font-weight: bold;">Imi doresc...
            <textarea name="detalii" id="detalii" rows="5" style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc;background: #faf9d9; ">
            <?php echo htmlspecialchars($detaliiExistente ?? ''); ?>
            </textarea>
            <button type="submit" class="upload-btn">Salvează</button>
        </form>
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
    $task_lc = strtolower(trim($task)); // Normalizează denumirea
$isChecked = isset($servicii_acceptate[$task_lc]);

echo '<div class="task">';
echo '<a href="categorii_f.php?serviciu=' . urlencode($task) . '" target="_blank">';
echo '<button>' . htmlspecialchars($task) . '</button>';
echo '</a>';

if ($isChecked) {
    echo '<span style="margin-left: 10px; margin-right: 10px; font-style: italic; color: #333;">' . htmlspecialchars($servicii_acceptate[$task_lc]) . '</span>';
}

echo '<input type="checkbox" disabled ' . ($isChecked ? 'checked' : '') . '>';
echo '</div>';

}


        ?>
    </div>
</main>
</body>
</html>
