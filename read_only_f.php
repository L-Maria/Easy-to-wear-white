<?php
session_start();
include 'config/db.php'; // include conexiunea
$notificare_trimisa= false;

if (!isset($_SESSION['user_id'])) {
    die("Eroare: utilizatorul nu este autentificat.");
}

$user_id = $_GET['id'] ?? $_SESSION['user_id'];
$sql = "SELECT nume, email, telefon, profile_pic  FROM furnizor WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($_SESSION['tip_utilizator'] !== 'mire') {
    die("Doar mirele poate trimite notificări.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trimite_notificare'])) {
    $furnizorId = (int)$_POST['notificare_furnizor_id'];
    $clientId = $_SESSION['user_id']; 

    // Obține numele mirelui din baza de date
    $stmtClient = $conn->prepare("SELECT nume FROM users WHERE id = ?");
    $stmtClient->bind_param("i", $clientId);
    $stmtClient->execute();
    $stmtClient->bind_result($numeClient);
    $stmtClient->fetch();
    $stmtClient->close();

    // Salvează notificarea
    $mesaj = "Clientul " . $numeClient . " dorește să colaboreze cu tine.";
    $stmt = $conn->prepare("INSERT INTO notificari (furnizor_id, client_id, mesaj, citita) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("iis", $furnizorId, $clientId, $mesaj);
    $stmt->execute();
    $stmt->close();


    $notificare_trimisa=true;
}




if ($row = $result->fetch_assoc()) {
    $nume = $row['nume'];
    $email = $row['email'];
    $telefon = $row['telefon'];
    $profile_pic=$row['profile_pic']??'default.png';
}



// Citește detaliile existente
$stmt = $conn->prepare("SELECT detalii FROM furnizor WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($detaliiExistente);
$stmt->fetch();
$stmt->close();



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profil Furnizor</title>

  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
  <link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css" /> -->

  <link rel="stylesheet" href="home_f.css?v=1.4">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Roboto&display=swap" rel="stylesheet">

</head>
<body>

<div class="container">
  <!-- Profile -->
  <div class="profile-card">
    <img src="uploads/<?php echo htmlspecialchars($profile_pic); ?>" alt="Poza Profil" class="profile-img">


    <h1><?php echo htmlspecialchars($nume); ?></h1>
    <p><?php echo htmlspecialchars($email); ?><br><?php echo htmlspecialchars($telefon); ?></p>

    <div style="margin-top: 1rem;">
  <label><strong>Detalii servicii:</strong></label>
  <p style="padding: 0.5rem; background: rgb(203, 218, 189); border-radius: 0.5rem; border: 1px solid rgb(161, 179, 146);">
    <?php echo nl2br(htmlspecialchars($detaliiExistente ?? 'Nu au fost adăugate detalii.')); ?>
  </p>
</div>
                  <div>
                    <form method="POST" style="margin-top: 15px;">
                    <input type="hidden" name="notificare_furnizor_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <button type="submit" name="trimite_notificare" class="upload-btn">Vreau să colaborăm</button>
                    </form>
                  </div>
                  <?php if ($notificare_trimisa): ?>
                  <div class="popup-notificare">
                    ✅ Notificarea a fost trimisă cu succes!
                  </div>
                  <?php endif; ?>

  </div>

  <!-- Main Content -->
  <div style="flex: 2 1 600px;">
    <div style="display: flex; gap: 1rem; justify-content: space-between; align-items: center;">
      </div>
            

    <!-- Gallery -->
    <div class="gallery-grid">
      <?php
      $stmt = $conn->prepare("SELECT path FROM images WHERE furnizor_id = ?");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()) {
        echo '<div class="gallery-item"><img src="' . htmlspecialchars($row['path']) . '" alt="Poza serviciu"></div>';
      }
      ?>
    </div>


    <!-- Comments -->
    <div class="comments">
      <h2 style= "color: #e8d29c">Recenzii:</h2>
      <?php
      $comments = $conn->prepare("
        SELECT c.continut, c.created_at, users.nume
        FROM comments c
        JOIN users ON c.users_id = users.id
        WHERE c.furnizor_id = ?
        ORDER BY c.created_at DESC
      ");
      $comments->bind_param("i", $user_id);
      $comments->execute();
      $commentsResult = $comments->get_result();

      if ($commentsResult->num_rows > 0) {
        while ($row = $commentsResult->fetch_assoc()) {
          $numeComment = htmlspecialchars($row['nume']);
          $text = htmlspecialchars($row['continut']);
          $createdAt = date("d M Y, H:i", strtotime($row['created_at']));
          echo '
          <div class="comment">
            <strong>' . $numeComment . '</strong> 
            <span style="color: #999; font-size: 0.85rem; margin-left: 1rem;">' . $createdAt . '</span>
            <p>' . $text . '</p>
          </div>';
        }
      } else {
        echo '<p style="color: #e8d29c;" class="text-muted">Nu există comentarii încă.</p>';
      }
      ?>
    </div>
  </div>
</div>

<script>
  function hideOnSelect(input) {
    if (input.files.length > 0) {
      document.getElementById("profilePicInputWrapper").style.display = "none";
    }
  }

  function hideGalleryOnSelect(input) {
    if (input.files.length > 0) {
      document.getElementById("galerieInputWrapper").style.display = "none";
    }
  }
</script>
</body>
</html>

            

 