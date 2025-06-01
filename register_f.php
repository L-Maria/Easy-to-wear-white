<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifică dacă toate câmpurile există în $_POST
    if (isset($_POST['nume'], $_POST['email'], $_POST['parola'], $_POST['parola2'], $_POST['telefon'], $_POST['serviciu'])) {
        // Verifică dacă parolele coincid
        if ($_POST['parola'] !== $_POST['parola2']) {
            $error = "Parolele nu coincid!";
        } else {
            // Conexiune la baza de date
            $conn = new mysqli("localhost", "root", "", "wedding_app");
            if ($conn->connect_error) {
                die("Conexiune eșuată: " . $conn->connect_error);
            }

            // Preluare și sanitizare date din formular
            $nume = $conn->real_escape_string($_POST['nume']);
            $email = $conn->real_escape_string($_POST['email']);
            $parola = password_hash($_POST['parola'], PASSWORD_DEFAULT);
            $telefon = $conn->real_escape_string($_POST['telefon']);
            $serviciu = $conn->real_escape_string($_POST['serviciu']);

            // Inserare în baza de date
            $sql = "INSERT INTO furnizor(nume, email, parola, telefon, serviciu)
                    VALUES ('$nume', '$email', '$parola', '$telefon', '$serviciu')";
            if ($conn->query($sql) === TRUE) {
                // Redirect după inserare cu succes
                $id_nou = $conn->insert_id;
                $_SESSION['user_id']= $id_nou;
                header("Location: home_f.php?id=" .$id_nou);
                exit();  // întotdeauna folosește exit după header redirect!
            } else {
                $error = "Eroare la crearea contului: " . $conn->error;
            }

            $conn->close();
        }
    } else {
        $error = "Toate câmpurile sunt obligatorii!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register</title>
  <link rel="stylesheet" href="register_m.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
</head>

<body>
<section class="vh-100 bg-image"
  style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px;">
            <div class="card-body p-5">
              <h2 class="text-uppercase text-center mb-5">Create an account</h2>

              <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
              <?php endif; ?>

              <form method="post" action="">

                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="text" id="nume" name="nume" class="form-control form-control-lg" required
                         value="<?= isset($_POST['nume']) ? htmlspecialchars($_POST['nume']) : '' ?>" />
                  <label class="form-label" for="nume">Numele tau</label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="email" id="email" name="email" class="form-control form-control-lg" required
                         value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" />
                  <label class="form-label" for="email">Email-ul tau</label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="password" id="parola" name="parola" class="form-control form-control-lg" required />
                  <label class="form-label" for="parola">Parola ta</label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="password" id="parola2" name="parola2" class="form-control form-control-lg" required />
                  <label class="form-label" for="parola2">Repeta parola</label>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                  <input type="tel" id="telefon" name="telefon" class="form-control form-control-lg" required
                    value="<?= isset($_POST['telefon']) ? htmlspecialchars($_POST['telefon']) : '' ?>" />
                  <label class="form-label" for="telefon">Numarul tau de telefon</label>
                </div>

                <div data-mdb-input-init class="form-group mb-4">
                  <label for="serviciu">Alege tipul de serviciu:</label>
                  <select class="form-control" name="serviciu" required>
                    <option value="">-- Selectează --</option>
                    <option value="1">Locatie</option>
                    <option value="2">Invitatii</option>
                    <option value="3">Decor</option>
                    <option value="4">Formatie</option>
                    <option value="5">Foto/Video</option>
                    <option value="5">Gustari</option>
                    <option value="4">Atelier</option>
                    <option value="5">Altele</option>
                  </select>
                </div>




                <div class="d-flex justify-content-center">
                  <button type="submit" data-mdb-button-init data-mdb-ripple-init
                    class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Register</button>
                </div>

                <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="#!"
                    class="fw-bold text-body"><u>Login here</u></a></p>

              </form>

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
