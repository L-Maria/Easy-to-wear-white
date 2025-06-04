<?php
session_start();
include 'config\db.php';

if (!isset($_SESSION['user_id'])) {
    die("Eroare: utilizatorul nu este autentificat. (SESSION[user_id] nu este setat)");
}


$user_id = $_SESSION['user_id'];

// Get furnizor info including profile_pic
$sql = "SELECT nume, email, telefon, profile_pic FROM furnizor WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Nu există niciun furnizor cu ID-ul: $user_id în baza de date.");
}

$row = $result->fetch_assoc();
if ($row) {
$nume = $row['nume'];
$email = $row['email'];
$telefon = $row['telefon'];
$profilePic = $row['profile_pic'] ?? 'default.jpg';
} else{
  $nume = $email = $telefon = 'N/A';
    $profilePic = 'default.jpg';
}

// Check if furnizor has any gallery images
$stmt = $conn->prepare("SELECT COUNT(*) FROM images WHERE furnizor_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($galleryCount);
$stmt->fetch();
$stmt->close();

// Update detalii
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['detalii'])) {
    $detalii = $conn->real_escape_string($_POST['detalii']);
    $stmt = $conn->prepare("UPDATE furnizor SET detalii = ? WHERE id = ?");
    $stmt->bind_param("si", $detalii, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Get existing detalii
$stmt = $conn->prepare("SELECT detalii FROM furnizor WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($detaliiExistente);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profil Furnizor</title>
  <link rel="stylesheet" href="home_f.css?v=1.3">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
  <!-- Profile -->
  <div class="profile-card">
    <img src="uploads/<?php echo htmlspecialchars($profilePic); ?>" alt="Poza Profil" class="profile-img">

    <form action="upload.php" method="post" enctype="multipart/form-data">
      <?php if ($profilePic === 'default.jpg') : ?>
        <div id="profilePicInputWrapper">
          <label for="profilePic" class="file-label">Alege o poză</label>
          <input type="file" name="profile_pic" id="profilePic" accept="image/*" onchange="hideOnSelect(this)">
          <div class="file-name" id="profilePicName">Nicio poză selectată</div>
        </div>
      <?php endif; ?>

      <button type="submit" class="upload-btn">Schimbă poza</button>
    </form>

    <h1><?php echo htmlspecialchars($nume); ?></h1>
    <p><?php echo htmlspecialchars($email); ?><br><?php echo htmlspecialchars($telefon); ?></p>

    <form method="post">
      <label for="detalii"><strong>Detalii servicii:</strong></label>
      <textarea name="detalii" id="detalii" rows="5" class="form-control" style="width:100%; padding:0.5rem; border-radius:0.5rem; border:1px solid rgb(161, 179, 146);  background: rgb(203, 218, 189);"><?php echo htmlspecialchars($detaliiExistente ?? ''); ?></textarea>
      <button type="submit" class="upload-btn" style="margin-top:1rem;">Salvează</button>
    </form>
  </div>

  <!-- Main Content -->
  <div style="flex: 2 1 600px;">
    <div style="display: flex; gap: 1rem; justify-content: space-between; align-items: center;">
      <form action="upload.php" method="POST" enctype="multipart/form-data">
        <div id="galerieInputWrapper">
          <label for="galerie" class="file-label">Selectează poze</label>
          <input type="file" name="images[]" id="galerie" accept="image/*" multiple onchange="hideGalleryOnSelect(this)">
          <?php if ($galleryCount == 0) : ?>
            <div class="file-name" id="galerieName">Nicio poză selectată</div>
          <?php endif; ?>
        </div>

        <button type="submit" class="upload-btn">Încarcă pozele</button>
      </form>

      <form action="notificari.php" method="get">
        <button type="submit" class="upload-btn">Notificări</button>
      </form>
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
        echo '<p class="text-muted" style="color: #e8d29c;">Nu există comentarii încă.</p>';
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