<?php
session_start();
include 'config/db.php'; // include conexiunea

if (!isset($_SESSION['user_id'])) {
    die("Eroare: utilizatorul nu este autentificat.");
}

$user_id = $_GET['id'] ?? $_SESSION['user_id'];
$sql = "SELECT nume, email, telefon  FROM furnizor WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trimite_notificare'])) {
    $furnizorId = (int)$_POST['notificare_furnizor_id'];
    $clientId = $_SESSION['user_id']; // 👈 AICI ERA LIPSA

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
}




if ($row = $result->fetch_assoc()) {
    $nume = $row['nume'];
    $email = $row['email'];
    $telefon = $row['telefon'];
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

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
  <link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css" />
  
  <style>
    .badge-outline-dark {
      color: #343a40;
      background-color: transparent;
      border: 1px solid #343a40;
      padding: 0.35em 0.65em;
      margin-right: 0.3em;
      border-radius: 0.25rem;
      font-size: 0.85em;
    }
    .profile-navbar .nav-link {
      color: #495057;
      font-weight: 500;
      padding: 0.5rem 1rem;
    }
    .profile-navbar .nav-link.active {
      color: #007bff;
      border-bottom: 2px solid #007bff;
    }
    .profile-feed-item {
      margin-bottom: 1.5rem;
    }
    .profile-feed-item img.img-sm {
      width: 40px;
      height: 40px;
    }
    .progress-md {
      height: 6px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-4">
              <div class="border-bottom text-center pb-4">
                <?php
                  $userId = $_SESSION['user_id'];
                  $result = $conn->query("SELECT profile_pic FROM furnizor WHERE id = $userId");
                  $row = $result->fetch_assoc();
                  $profilePic = $row['profile_pic'] ?? 'default.jpg';
                ?>
                <img src="uploads/<?php echo htmlspecialchars($profilePic); ?>" alt="profile" class="img-lg rounded-circle mb-3" style="width:120px; height:120px;">

                <div class="mb-3">
                  <h3><?php echo htmlspecialchars($nume); ?></h3>
                  <div class="d-flex align-items-center justify-content-center">
                    <h5 class="mb-0 mr-2 text-muted">oras domiciliu</h5>
                  </div>
                </div>
              </div>

              <div class="border-bottom py-4">
                <p class="text-muted medium mb-1"><?php echo htmlspecialchars($email); ?></p>
                <p class="text-muted medium"><?php echo htmlspecialchars($telefon); ?></p>
              </div>

              <div style="max-width: 500px; margin-top: 20px;">
                <label style="font-weight: bold;">Detalii servicii:</label>
                <div style="white-space: pre-wrap; padding: 10px; border-radius: 6px; border: 1px solid #ccc;">
                  <?php echo htmlspecialchars($detaliiExistente ?? ''); ?>
                </div>
              </div>

                <form method="POST" style="margin-top: 15px;">
                    <input type="hidden" name="notificare_furnizor_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <button type="submit" name="trimite_notificare" class="btn btn-primary">Vreau să colaborăm</button>
                </form>

            </div>


            

            <div class="col-lg-8">
              <div class="profile-feed mt-5">
                <div style="padding: 20px;">
                  <?php
                    if ($conn->connect_error) {
                      die("Conexiune eșuată: " . $conn->connect_error);
                    }

                    $furnizor_id = $_SESSION['user_id'];
                    $stmt = $conn->prepare("SELECT path FROM images WHERE furnizor_id = ?");
                    $stmt->bind_param("i", $furnizor_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">';
                    while ($row = $result->fetch_assoc()) {
                      echo '<div class="image-box">';
                      echo '<img src="' . htmlspecialchars($row['path']) . '" style="width: 100%; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1);" />';
                      echo '</div>';
                    }
                    echo '</div>';
                    $conn->close();
                  ?>

                  <style>
                    .image-box {
                      overflow: hidden;
                      transition: transform 0.3s;
                    }
                    .image-box:hover {
                      transform: scale(1.03);
                    }
                  </style>
                </div>

                <div class="d-flex align-items-start profile-feed-item mt-5">
                  <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="profile" class="img-sm rounded-circle">
                  <div class="ml-4">
                    <h6>
                      Willie Stanley
                      <small class="ml-4 text-muted"><i class="mdi mdi-clock mr-1"></i>10 hours</small>
                    </h6>
                    <img src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="sample" class="rounded mw-100">                            
                    <p class="small text-muted mt-2 mb-0">
                      <span><i class="mdi mdi-star mr-1"></i>4</span>
                      <span class="ml-2"><i class="mdi mdi-comment mr-1"></i>11</span>
                      <span class="ml-2"><i class="mdi mdi-reply"></i></span>
                    </p>
                  </div>
                </div>

                <div class="d-flex align-items-start profile-feed-item">
                  <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="profile" class="img-sm rounded-circle">
                  <div class="ml-4">
                    <h6>
                      Dylan Silva
                      <small class="ml-4 text-muted"><i class="mdi mdi-clock mr-1"></i>10 hours</small>
                    </h6>
                    <p>
                      When I first got into the online advertising business, I was looking for the magical combination 
                      that would put my website into the top search engine rankings
                    </p>
                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="sample" class="rounded mw-100">                                                        
                    <p class="small text-muted mt-2 mb-0">
                      <span><i class="mdi mdi-star mr-1"></i>4</span>
                      <span class="ml-2"><i class="mdi mdi-comment mr-1"></i>11</span>
                      <span class="ml-2"><i class="mdi mdi-reply"></i></span>
                    </p>
                  </div>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>