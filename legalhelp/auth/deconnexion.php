<?php

require_once __DIR__ . '/../bootstrap.php';
clear_authenticated_user();
session_destroy();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Deconnexion - LegaliaBot</title>
  <style>
    body {
      margin: 0;
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 20px;
      font-family: "Montserrat", "Segoe UI", sans-serif;
      color: #17324a;
      background:
        radial-gradient(circle at top left, rgba(105, 184, 202, 0.2), transparent 30%),
        linear-gradient(180deg, #fbfdff 0%, #edf4fb 100%);
    }

    .logout-card {
      width: min(100%, 520px);
      padding: 32px;
      border-radius: 28px;
      background: rgba(255, 255, 255, 0.92);
      text-align: center;
      box-shadow: 0 24px 60px rgba(23, 50, 74, 0.12);
    }

    .spinner {
      width: 58px;
      height: 58px;
      margin: 0 auto 18px;
      border-radius: 50%;
      border: 5px solid rgba(24, 79, 120, 0.15);
      border-top-color: #1d5f91;
      animation: spin 1s linear infinite;
    }

    h1 {
      margin: 0 0 12px;
    }

    p {
      margin: 0;
      color: #60798c;
      line-height: 1.7;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
  </style>
</head>
<body>
  <div class="logout-card">
    <div class="spinner"></div>
    <h1>Deconnexion en cours</h1>
    <p>Nous fermons votre session Firebase puis nous vous ramenons vers l'accueil.</p>
  </div>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js";
    import { getAuth, signOut } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-auth.js";

    const firebaseConfig = <?= firebase_web_config_json() ?>;
    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    setTimeout(async () => {
      try {
        await signOut(auth);
      } catch (error) {
        console.error("Erreur lors de la deconnexion Firebase :", error);
      } finally {
        window.location.href = "../acceuil/index.php";
      }
    }, 900);
  </script>
</body>
</html>
