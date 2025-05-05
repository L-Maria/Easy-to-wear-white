<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="register_m.css"> <!-- LEGĂTURA CU CSS -->
  <!-- Bootstrap (dacă folosești MDB) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet"/>
  <style>
    /* Asigurăm că formularul va fi scrollabil fără a afecta alte elemente */
    .form-container {
      max-height: 80vh;  /* Se ajustează în funcție de înălțimea dorită */
      overflow-y: auto;  /* Permite scroll vertical când formularul este mai mare decât containerul */
    }

    /* Stilizare pentru card pentru a rămâne pe centrul paginii */
    .card {
      border-radius: 15px;
    }

    /* Dacă dorim să menținem background-ul fix */
    .bg-image {
      background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');
      background-attachment: fixed;
    }
  </style>
</head>

<body>

<section class="bg-image vh-100">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card">
            <div class="card-body p-5 form-container">
              <h2 class="text-uppercase text-center mb-5">Create an account</h2>

              <form>
                <div class="mb-4">
                  <label class="form-label" for="form3Example1cdg">Nume</label>
                  <input type="text" id="form3Example1cdg" class="form-control form-control-lg" />
                </div>

                <div class="mb-4">
                  <label class="form-label" for="form3Example2cdg">Email</label>
                  <input type="text" id="form3Example2cdg" class="form-control form-control-lg" />
                </div>

                <div class="mb-4">
                  <label class="form-label" for="form3Example6cdg">Parola</label>
                  <input type="password" id="form3Example6cdg" class="form-control form-control-lg" />
                </div>

                <div class="mb-4">
                  <label class="form-label" for="form3Example4cdg">Numar de telefon</label>
                  <input type="tel" id="form3Example4cdg" class="form-control form-control-lg" />
                </div>

                <div class="mb-4">
                  <label class="form-label" for="form3Example5cdg">Religie</label>
                  <input type="text" id="form3Example5cdg" class="form-control form-control-lg" />
                </div>

                <div class="mb-4">
                  <label class="form-label" for="form3Example6cdg">Data nuntii</label>
                  <input type="date" id="form3Example6cdg" class="form-control form-control-lg" />
                </div>

                <div class="mb-4">
                  <label class="form-label" for="form3Example7cdg">Locatie</label>
                  <input type="text" id="form3Example7cdg" class="form-control form-control-lg" />
                </div>

                <div class="mb-4">
                  <label class="form-label" for="form3Example8cdg">Numar de invitati</label>
                  <input type="text" id="form3Example8cdg" class="form-control form-control-lg" />
                </div>

                <div class="d-flex justify-content-center">
                  <button type="button" data-mdb-button-init
                    data-mdb-ripple-init class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Register</button>
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

</body>
</html>
