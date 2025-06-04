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

  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />


  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />

  <style>
   /* Fundal transparent pentru toate inputurile și selectul */
.form-control {
  background-color: transparent !important;
  color: black !important;
  border: 1px solid rgba(255, 255, 255, 0.3) !important;
}

/* Fundalul intern adăugat de MDB - override direct containerul */
.form-outline .form-control {
  background-color: transparent !important;
  box-shadow: none !important;
  color: black !important;
}

/* Textul în interiorul inputurilor (placeholder/label animat) */
.form-label {
  color: black !important;
}

/* Borduri focus - opțional: eliminăm culoarea albastră mdb */
.form-outline .form-control:focus {
  border-color: rgba(0, 0, 0, 0.5) !important;
  box-shadow: none !important;
}

/* Select transparent + text negru */
select.form-control {
  background-color: transparent !important;
  color: black !important;
  border: 1px solid rgba(255, 255, 255, 0.3) !important;
}

/* Label "Alege tipul de serviciu:" */
.form-group > label {
  color: black !important;
}

.form-outline .form-control::placeholder {
  color: black !important;
  opacity: 1 !important;
}


  </style>


</head>
<style>
.mask {
      background: transparent;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
</style>
<body>
<section class="bg-login" style="background-image: url('images/furnizori.jpg'); 
                                  background-size: 50%;  
                                  background-position: 50% 50%; 
                                  height: 100vh;">
  <div class="mask">                      
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100" >
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px; background-color: rgba(255, 255, 255, 0.1); backdrop-filter: blur(35px); border: 1px solid rgba(255, 255, 255, 0.2);">
            <div class="card-body p-5" >
              <h2 class="text-uppercase text-center mb-5" style="color:rgb(0, 0, 0);">Creeaza cont</h2>

              <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
              <?php endif; ?>

              <form method="post" action="">

                <div class="form-outline mb-4">
                  <input type="text" id="nume" name="nume" class="form-control form-control-lg" required
                         value="<?= isset($_POST['nume']) ? htmlspecialchars($_POST['nume']) : '' ?>" />
                  <label class="form-label" for="nume" >Numele tau</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="email" id="email" name="email" class="form-control form-control-lg" required
                         value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" />
                  <label class="form-label" for="email">Email-ul tau</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="password" id="parola" name="parola" class="form-control form-control-lg" required />
                  <label class="form-label" for="parola">Parola ta</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="password" id="parola2" name="parola2" class="form-control form-control-lg" required />
                  <label class="form-label" for="parola2">Repeta parola</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="tel" id="telefon" name="telefon" class="form-control form-control-lg" required
                    value="<?= isset($_POST['telefon']) ? htmlspecialchars($_POST['telefon']) : '' ?>" />
                  <label class="form-label" for="telefon">Numarul tau de telefon</label>
                </div>

                <div class="form-outline mb-4">
                  <label for="serviciu">Alege tipul de serviciu:</label>
                  <select class="form-control" name="serviciu" required>
                    <option value="">-- Selectează --</option>
                    <option value="Locatie">Locatie</option>
                    <option value="Invitatii">Invitatii</option>
                    <option value="Decor">Decor</option>
                    <option value="Formatie">Formatie</option>
                    <option value="Foto/Video">Foto/Video</option>
                    <option value="Gustari">Gustari</option>
                    <option value="Atelier">Atelier</option>
                    <option value="Altele">Altele</option>
                  </select>
                </div>




                <div class="d-flex justify-content-center">
                  <button type="submit" class="btn  btn-block btn-lg  text-body">Inregistrare </button>
                </div>

                

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
