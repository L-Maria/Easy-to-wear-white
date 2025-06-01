
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
        }

        // Verifică parola
        if (password_verify($parola, $parola_hash)) {
                $_SESSION['users_id'] = $id;
                $_SESSION['users_nume'] = $nume;
                $_SESSION['tip_utilizator'] = $tip_utilizator;

                header("Location: $redirect");
                exit();
            } else {
                $eroare = "Parolă incorectă.";
            }
        } else {
            $eroare = "Emailul nu a fost găsit.";
        }

        $stmt->close();
    
    $conn->close();
    }


?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Easy to wear white</title>

  <!-- MDBootstrap CSS -->
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css"
    rel="stylesheet"
  />

  <!-- Fundal cu imagine -->
  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      background-image: url("images/background.jpg");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.10);
      color: white;
    }

    .text-white-bg {
      background-color: rgba(255, 255, 255, 0.10);
      color: white;
    }
  </style>
</head>

<body>
  <section class="h-100 d-flex align-items-center" style="background-color: transparent;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
          <div class="card rounded-3 text-black">
            <div class="row g-0">
              <div class="col-lg-6">
                <div class="card-body p-md-5 mx-md-4">

                  <div class="text-center">
                    <img src="images/verighete.png"
                      style="width: 185px;" alt="logo">
                    <h4 class="mt-1 mb-5 pb-1">We are The Lotus Team</h4>
                  </div>

                  <form method="POST" action="index.php">
                    <p>Please login to your account</p>

                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="email" name="email" class="form-control" required/>
                      <label class="form-label" for="email">Username</label>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="password" name="parola" class="form-control" required/>
                      <label class="form-label" for="form2Example22">Password</label>
                    </div>

                    <div class="form-outline mb-4">
                      <select name="tip_utilizator" class="form-select" required>
                        <option value="" disabled selected>Alege tipul de utilizator</option>
                        <option value="mire">👰 Mire</option>
                        <option value="furnizor">💼 Furnizor</option>
                      </select>
                    </div>


                    <div class="text-center pt-1 mb-5 pb-1">
                      <button type="submit" name="login" class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3">Logare</button>
                    </div>




                  </form>

                  <div class="d-flex align-items-center justify-content-center pb-4">
                      <p class="mb-0 me-2">Don't have an account?</p>
                      
                      <form method="get" action="register_m.php" style="display: inline;">
                          <button type="submit" class="btn btn-outline-danger ms-2">Crează cont mire</button>
                      </form>

                      <form method="get" action="register_f.php" style="display: inline;">
                          <button type="submit" class="btn btn-outline-danger ms-2">Crează cont furnizor</button>
                      </form>
                    </div>

                    

                  


                </div>
              </div>
    
              <div class="col-lg-6 d-flex align-items-center text-white-bg">
                <div class="px-3 py-4 p-md-5 mx-md-4">
                  <h4 class="mb-4">We are more than just a company</h4>
                  <p class="small mb-0">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                    exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- MDBootstrap JS -->
  <script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"
  ></script>
</body>
</html>
