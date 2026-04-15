<?php

require_once __DIR__ . '/../bootstrap.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creer un compte - LegaliaBot</title>
  <link rel="stylesheet" href="../styles/auth.css">
</head>
<body>
  <div class="auth-card">
    <div class="top-bar">
      <a href="../acceuil/index.php">Retour</a>
      <h1>Creer un compte</h1>
    </div>

    <p class="auth-intro">Creez votre espace en quelques secondes pour retrouver votre historique et utiliser le chatbot sur tous vos appareils.</p>

    <div class="form-box">
      <input type="text" id="displayName" placeholder="Nom ou pseudo">
      <input type="email" id="email" placeholder="Email">
      <input type="password" id="password" placeholder="Mot de passe">
      <input type="password" id="confirm" placeholder="Confirmez le mot de passe">
      <button class="primary-button signup-btn" type="button">S'inscrire</button>
      <p id="message" class="status-line" aria-live="polite"></p>
    </div>

    <footer>
      <p>Vous avez deja un compte ? <a href="authentification.php">Connectez-vous</a></p>
    </footer>
  </div>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js";
    import {
      createUserWithEmailAndPassword,
      getAuth,
      onAuthStateChanged,
      updateProfile
    } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-auth.js";
    import {
      doc,
      getFirestore,
      serverTimestamp,
      setDoc
    } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-firestore.js";

    const firebaseConfig = <?= firebase_web_config_json() ?>;
    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const db = getFirestore(app);

    const displayNameInput = document.getElementById("displayName");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const confirmInput = document.getElementById("confirm");
    const signupBtn = document.querySelector(".signup-btn");
    const message = document.getElementById("message");

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

    signupBtn.addEventListener("click", async () => {
      const displayName = displayNameInput.value.trim();
      const email = emailInput.value.trim();
      const password = passwordInput.value;
      const confirmPassword = confirmInput.value;

      setMessage("", "");

      if (!email || !password || !confirmPassword) {
        setMessage("Veuillez remplir tous les champs.", "error");
        return;
      }

      if (password !== confirmPassword) {
        setMessage("Les mots de passe ne correspondent pas.", "error");
        return;
      }

      if (password.length < 8) {
        setMessage("Le mot de passe doit contenir au moins 8 caracteres.", "error");
        return;
      }

      try {
        const userCredential = await createUserWithEmailAndPassword(auth, email, password);
        const user = userCredential.user;
        const finalDisplayName = displayName || email.split("@")[0];

        await updateProfile(user, { displayName: finalDisplayName });
        await setDoc(doc(db, "users", user.uid), {
          username: finalDisplayName,
          email: user.email,
          phone: "",
          createdAt: serverTimestamp(),
          updatedAt: serverTimestamp()
        }, { merge: true });

        await syncServerSession(user);
        setMessage("Compte cree avec succes. Redirection...", "success");

        setTimeout(() => {
          window.location.href = "../acceuil/accueil.php";
        }, 1000);
      } catch (error) {
        setMessage(`Erreur : ${error.message}`, "error");
      }
    });
  </script>
</body>
</html>
