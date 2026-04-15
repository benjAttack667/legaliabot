<?php

require_once __DIR__ . '/../bootstrap.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - LegaliaBot</title>
  <link rel="stylesheet" href="../styles/auth.css">
</head>
<body>
  <div class="auth-card">
    <div class="top-bar">
      <a href="../acceuil/index.php">Retour</a>
      <h1>Connexion</h1>
    </div>

    <p class="auth-intro">Connectez-vous pour retrouver votre espace personnel, votre historique de conversation et vos parametres.</p>

    <div class="form-box">
      <input type="email" id="email" placeholder="Email">
      <input type="password" id="password" placeholder="Mot de passe">
      <button class="primary-button signin-btn" type="button">Se connecter</button>
      <p id="message" class="status-line" aria-live="polite"></p>
    </div>

    <footer>
      <p>Pas encore de compte ? <a href="connexion.php">Inscrivez-vous</a></p>
    </footer>
  </div>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js";
    import { getAuth, onAuthStateChanged, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-auth.js";

    const firebaseConfig = <?= firebase_web_config_json() ?>;
    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const message = document.getElementById("message");
    const signinBtn = document.querySelector(".signin-btn");

    function setMessage(text, tone = "") {
      message.textContent = text;
      message.className = `status-line ${tone}`.trim();
    }

    async function syncServerSession(user) {
      const idToken = await user.getIdToken();
      const response = await fetch("session.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ id_token: idToken })
      });

      return response.json();
    }

    onAuthStateChanged(auth, async (user) => {
      if (user) {
        const result = await syncServerSession(user);
        if (result.ok) {
          window.location.href = "../acceuil/accueil.php";
        }
      }
    });

    signinBtn.addEventListener("click", async () => {
      const email = emailInput.value.trim();
      const password = passwordInput.value;

      setMessage("", "");

      if (!email || !password) {
        setMessage("Veuillez remplir tous les champs.", "error");
        return;
      }

      try {
        const userCredential = await signInWithEmailAndPassword(auth, email, password);
        const result = await syncServerSession(userCredential.user);

        if (!result.ok) {
          setMessage(result.error || "Impossible d'ouvrir la session.", "error");
          return;
        }

        setMessage("Connexion reussie. Redirection...", "success");
        setTimeout(() => {
          window.location.href = "../acceuil/accueil.php";
        }, 700);
      } catch (error) {
        setMessage(`Erreur : ${error.message}`, "error");
      }
    });
  </script>
</body>
</html>
