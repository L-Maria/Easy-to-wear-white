<?php
   session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifică dacă toate câmpurile există în $_POST
    if (isset($_POST['nume'], $_POST['email'], $_POST['parola'], $_POST['telefon'], $_POST['religie'], $_POST['data_nunta'], $_POST['locatie'], $_POST['invitati'])) {
        // Conexiune la baza de date
        $conn = new mysqli("localhost", "root", "", "mire_db");
        if ($conn->connect_error) {
            die("Conexiune eșuată: " . $conn->connect_error);
        }

        // Preluare și sanitizare date din formular
        $nume = $conn->real_escape_string($_POST['nume']);
        $email = $conn->real_escape_string($_POST['email']);
        $parola = password_hash($_POST['parola'], PASSWORD_DEFAULT);
        $telefon = $conn->real_escape_string($_POST['telefon']);
        $religie = $conn->real_escape_string($_POST['religie']);
        $data_nunta = $conn->real_escape_string($_POST['data_nunta']);
        $locatie = $conn->real_escape_string($_POST['locatie']);
        $invitati = (int) $_POST['invitati'];

        // Inserare în baza de date
        $sql = "INSERT INTO users (nume, email, parola, telefon, religie, data_nunta, locatie, invitati)
                VALUES ('$nume', '$email', '$parola', '$telefon', '$religie', '$data_nunta', '$locatie', $invitati)";
        if ($conn->query($sql) === TRUE) {
            // Redirect după inserare cu succes
            header("Location: home_m.php");
            $_SESSION['user_id'] = $conn->insert_id;
            exit();  // întotdeauna folosește exit după header redirect!
        } else {
            echo "<p style='color: red; text-align: center;'>Error: " . $conn->error . "</p>";
        }

        $conn->close();
    } else {
        echo "<p style='color: red; text-align: center;'>Toate câmpurile sunt obligatorii!</p>";
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
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    .bg-image {
      background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');
      background-size: cover;
      background-position: center;
      min-height: 100vh;
    }

    .mask {
      background: rgba(132, 250, 176, 0.5);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .card {
      border-radius: 15px;
      max-width: 480px;
      width: 100%;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .card-body {
      max-height: 80vh;
      overflow-y: auto;
      padding: 2rem !important;
    }

    .form-label {
      font-weight: 600;
    }
  </style>
</head>

<body>
  <section class="bg-image">
    <div class="mask">
      <div class="card">
        <div class="card-body">
          <h2 class="text-uppercase text-center mb-5">Create an account</h2>

          <form method="POST" action="">
            <div class="mb-4">
              <label for="nume" class="form-label">Nume</label>
              <input type="text" id="nume" name="nume" class="form-control form-control-lg" required />
            </div>

            <div class="mb-4">
              <label for="email" class="form-label">Email</label>
              <input type="email" id="email" name="email" class="form-control form-control-lg" required />
            </div>

            <div class="mb-4">
              <label for="parola" class="form-label">Parola</label>
              <input type="password" id="parola" name="parola" class="form-control form-control-lg" required />
            </div>

            <div class="mb-4">
              <label for="telefon" class="form-label">Numar de telefon</label>
              <input type="tel" id="telefon" name="telefon" class="form-control form-control-lg" required />
            </div>

            <div class="mb-4">
              <label for="religie" class="form-label">Religie</label>
              <input type="text" id="religie" name="religie" class="form-control form-control-lg" required />
            </div>

            <div class="mb-4">
              <label for="data_nunta" class="form-label">Data nuntii</label>
              <input type="date" id="data_nunta" name="data_nunta" class="form-control form-control-lg" required />
            </div>

            <div class="mb-4">
              <label for="locatie" class="form-label">Locatie</label>
              <input type="text" id="locatie" name="locatie" class="form-control form-control-lg" required />
            </div>

            <div class="mb-4">
              <label for="invitati" class="form-label">Numar de invitati</label>
              <input type="number" id="invitati" name="invitati" class="form-control form-control-lg" min="1" required />
            </div>

            <div class="d-flex justify-content-center">
              <button type="submit" class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">
                Register
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </section>
</body>

</html>
