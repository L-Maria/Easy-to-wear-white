<?php
    session_start();
 
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['creare_mire'])) {
        include("register_m.php");
        exit();
        }



    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['creare_furnizor'])) {
        include("register_f.php");
        exit();
        }

  
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $conn = new mysqli("localhost", "root", "", "wedding_app");

      if ($conn->connect_error) {
        die("Conexiunea a eșuat: " . $conn->connect_error);
      }

      $email = $conn->real_escape_string($_POST['email']);
      $parola = $_POST['parola'];
      $tip_utilizator = $_POST['tip_utilizator'];

      // Caută utilizatorul după email
      if($tip_utilizator === 'mire'){
        $sql = "SELECT id, nume, parola FROM users WHERE email = ?";
        $redirect = "home_m.php";
      }
      elseif($tip_utilizator === 'furnizor'){
        $sql = "SELECT id, nume, parola FROM furnizor WHERE email = ?";
        $redirect = "home_f.php";
      }
      else {
        $eroare= "Tip utilizator invalid";
      }

      if(!isset($eroare)){
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();


        if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $nume, $parola_hash);
    $stmt->fetch();

    // Verifică parola
          if (password_verify($parola, $parola_hash)) {
              $_SESSION['user_id'] = $id;
              $_SESSION['user_nume'] = $nume;
              $_SESSION['tip_utilizator'] = $tip_utilizator;

              header("Location: $redirect");
              exit();
          } else {
              $eroare = "Parolă incorectă.";
          }
      } else {
          $eroare = "Emailul nu a fost găsit.";
      }
    }
        $stmt->close();
    
    $conn->close();
    }


?>


<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Easy to wear white</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Great+Vibes&family=Open+Sans&display=swap" rel="stylesheet">

  <!-- MDBootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="index.css?v=3" />
</head>

<body>
  <section class="h-100 d-flex align-items-center main-section">
    <div class="container py-5 h-100">
      <div class="row justify-content-center align-items-center h-100">
        <div class="col-xl-10">
          <div class="card shadow-lg wedding-card">
            <div class="row g-0">

              <!-- Left Panel -->
              <div class="col-lg-6 p-5 left-panel">
                <div class="text-center mb-5 logo-area">
                  <img src="images/verighete.png" alt="logo" class="logo-img" />
                  <h1 class="title">Easy to wear white</h1>
                </div>

                <form method="POST" action="index.php" class="login-form">
                  <div class="form-outline mb-4">
                    <input type="email" name="email" class="form-control" placeholder="Email" required />
                    <label class="form-label">Email</label>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" name="parola" class="form-control" placeholder="Parola" required />
                    <label class="form-label">Parola</label>
                  </div>

                  <div class="form-outline mb-4">
                    <select name="tip_utilizator" class="form-select" required>
                      <option value="" disabled selected>Tip utilizator</option>
                      <option value="mire">👰 Mire</option>
                      <option value="furnizor">💼 Furnizor</option>
                    </select>
                  </div>

                  <div class="text-center mb-4">
                    <button type="submit" name="login" class="btn btn-primary btn-gradient w-100">Autentificare</button>
                  </div>
                </form>

                <div class="d-flex justify-content-between register-buttons">
                  <form method="get" action="register_m.php">
                    <button type="submit" class="btn btn-outline-primary">Crează cont mire</button>
                  </form>
                  <form method="get" action="register_f.php">
                    <button type="submit" class="btn btn-outline-primary">Crează cont furnizor</button>
                  </form>
                </div>
              </div>

              <!-- Right Panel -->
              <div class="col-lg-6 right-panel d-flex align-items-center justify-content-center">
                <div class="right-content">
                  <h2>Eleganță și Emoție</h2>
                  <p>
                    Transformă-ți nunta într-o poveste de vis. Conectăm miri cu furnizori premium, pentru momente de neuitat, într-o experiență elegantă și intuitivă.
                  </p>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>
</html>