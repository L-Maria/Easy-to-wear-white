<?php
session_start();
include 'config\db.php';

$furnizor_id = $_SESSION['user_id']; // furnizorul logat

// Preluăm și client_id pentru a putea construi linkul către profil
$stmt = $conn->prepare("SELECT id, mesaj, data, citita, client_id FROM notificari WHERE furnizor_id = ? ORDER BY data DESC");
$stmt->bind_param("i", $furnizor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Notificările Mele</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
</head>
<body>
<div class="container mt-5">
  <h2>Notificări primite</h2>
  <ul class="list-group">
    <?php while ($row = $result->fetch_assoc()): ?>
      <li class="list-group-item <?php echo $row['citita'] ? '' : 'font-weight-bold'; ?>">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <?php echo htmlspecialchars($row['mesaj']); ?>
            <div class="text-muted small"><?php echo $row['data']; ?></div>
          </div>
          <div class="btn-group" role="group">
            <form method="POST" action="accepta_oferta.php" style="margin-right: 5px;">
              <input type="hidden" name="notificare_id" value="<?php echo $row['id']; ?>">
              <button type="submit" class="btn btn-success btn-sm">Acceptă oferta</button>
            </form>
            <a href="home_m.php?id=<?php echo $row['client_id']; ?>" class="btn btn-primary btn-sm">Vezi profil</a>
          </div>
        </div>
      </li>
    <?php endwhile; ?>
  </ul>
</div>
</body>
</html>
