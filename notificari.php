<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Notificări Site</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #007bff;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .container {
      max-width: 700px;
      margin: 30px auto;
      padding: 0 20px;
    }

    .notification {
      background-color: white;
      border-left: 5px solid #007bff;
      padding: 15px 20px;
      margin-bottom: 15px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      transition: background-color 0.3s;
    }

    .notification:hover {
      background-color: #f1f9ff;
    }

    .notification h3 {
      margin: 0 0 5px;
      font-size: 1.1rem;
    }

    .notification time {
      font-size: 0.85rem;
      color: #6c757d;
    }
  </style>
</head>
<body>
  <header>
    <h1>Notificări</h1>
  </header>

  <div class="container">
    <div class="notification">
      <h3>Actualizare sistem</h3>
      <p>Site-ul va fi în mentenanță pe 5 iunie între orele 01:00 și 03:00.</p>
      <time>1 iunie 2025, 12:30</time>
    </div>

    <div class="notification">
      <h3>Funcționalitate nouă</h3>
      <p>Am adăugat opțiunea de autentificare cu Google!</p>
      <time>30 mai 2025, 09:00</time>
    </div>
  </div>
</body>
</html>
