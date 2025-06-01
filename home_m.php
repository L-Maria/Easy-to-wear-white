<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Nu este identificat niciun utilizator.";
    exit();
}

$host = 'localhost';
$db = 'wedding_app';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}

$user_id = intval($_SESSION['user_id']);
$sql_user = "SELECT * FROM users WHERE id = $user_id";
$res_user = $conn->query($sql_user);

if ($res_user->num_rows != 1) {
    echo "Userul nu a fost găsit.";
    exit();
}

$user_data = $res_user->fetch_assoc();
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
            "Haine Mire/Mireasa",
            "Haine Domnisoare de Onoare/Cavaleri de Onoare"
        ];

        foreach ($taskuri as $task) {
            echo '<div class="task">';
            echo "<button>$task</button>";
            echo '<input type="checkbox">';
            echo '</div>';
        }
        ?>
    </div>
</main>
</body>
</html>
