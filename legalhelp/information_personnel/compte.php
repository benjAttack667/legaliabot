<?php

require_once __DIR__ . '/../bootstrap.php';

$user = current_user();
$displayName = $user['name'] ?? 'Utilisateur';
$initial = strtoupper(substr($displayName, 0, 1));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Mon compte - LegaliaBot</title>
  <link rel="stylesheet" href="../styles/style.css" />
  <link rel="stylesheet" href="../styles/account.css" />
</head>
<body data-user-uid="<?= h($user['uid'] ?? '') ?>">
  <div class="container">
    <?php include '../sidebar.php'; ?>

    <main class="content">
      <section class="page-hero">
        <h1>Mon compte</h1>
        <p>Gerez vos informations personnelles, mettez a jour vos donnees et gardez un acces rapide a votre espace juridique sur tous les ecrans.</p>
      </section>

      <?php if (!$user): ?>
        <section class="panel auth-notice">
          <h2>Connexion requise</h2>
          <p>Connectez-vous pour acceder a votre profil, modifier vos informations et synchroniser votre compte avec Firestore.</p>
          <div class="button-row">
            <a class="btn-primary" href="../auth/authentification.php">Se connecter</a>
            <a class="btn-secondary" href="../auth/connexion.php">Creer un compte</a>
          </div>
        </section>
      <?php else: ?>
        <section class="account-grid">
          <div class="account-stack">
            <article class="panel account-card">
              <h2>Informations personnelles</h2>
              <p>Vos informations sont synchronisees avec Firebase Authentication et votre fiche utilisateur Firestore.</p>

              <form id="infosForm" class="account-form">
                <label for="nom">
                  Nom affiche
                  <input id="nom" type="text" placeholder="Votre nom ou pseudo" required />
                </label>

                <label for="email">
                  Adresse e-mail
                  <input id="email" type="email" placeholder="exemple@mail.com" required />
                </label>

                <label for="tel">
                  Telephone
                  <input id="tel" type="tel" placeholder="06 00 00 00 00" />
                </label>

                <button type="submit" class="btn-primary">Enregistrer les modifications</button>
                <div id="infosMsg" class="status-message" aria-live="polite"></div>
              </form>
            </article>

            <article class="panel account-card">
              <h2>Securite</h2>
              <p>Pour modifier votre mot de passe, Firebase demande une re-authentification avec votre mot de passe actuel.</p>

              <form id="passwordForm" class="account-form">
                <label for="currentPassword">
                  Mot de passe actuel
                  <input id="currentPassword" type="password" required />
                </label>

                <label for="newPassword">
                  Nouveau mot de passe
                  <input id="newPassword" type="password" minlength="8" required />
                </label>

                <label for="confirmPassword">
                  Confirmer le nouveau mot de passe
                  <input id="confirmPassword" type="password" minlength="8" required />
                </label>

                <button type="submit" class="btn-primary">Modifier le mot de passe</button>
                <div id="passwordMsg" class="status-message" aria-live="polite"></div>
              </form>
            </article>
          </div>

          <aside class="account-stack">
            <article class="panel account-side-card profile-summary">
              <div class="avatar-badge" id="avatarBadge"><?= h($initial) ?></div>
              <div>
                <h2 id="summaryName"><?= h($displayName) ?></h2>
                <p id="summarySubtitle">Compte utilisateur Firebase</p>
              </div>

              <div class="summary-list">
                <div class="summary-item">
                  <span>Email</span>
                  <strong id="summaryEmail"><?= h($user['email'] ?? 'Non renseigne') ?></strong>
                </div>

                <div class="summary-item">
                  <span>Telephone</span>
                  <strong id="summaryPhone">A completer</strong>
                </div>

                <div class="summary-item">
                  <span>Statut</span>
                  <strong>Session securisee</strong>
                </div>
              </div>

              <a href="../auth/deconnexion.php" class="btn-secondary logout-link">Se deconnecter</a>
            </article>

            <article class="panel account-side-card">
              <h2>Bonnes pratiques</h2>
              <p>Utilisez un mot de passe unique, gardez vos informations a jour et pensez a supprimer votre historique si vous utilisez un appareil partage.</p>
            </article>
          </aside>
        </section>
      <?php endif; ?>
    </main>
  </div>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js";
    import {
      EmailAuthProvider,
      getAuth,
      onAuthStateChanged,
      reauthenticateWithCredential,
      updateEmail,
      updatePassword,
      updateProfile
    } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-auth.js";
    import {
      doc,
      getDoc,
      getFirestore,
      serverTimestamp,
      setDoc
    } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-firestore.js";

    const firebaseConfig = <?= firebase_web_config_json() ?>;
    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const db = getFirestore(app);
    const currentUserUid = document.body.dataset.userUid || "";

    const nomInput = document.getElementById("nom");
    const emailInput = document.getElementById("email");
    const telInput = document.getElementById("tel");
    const infosMsg = document.getElementById("infosMsg");
    const passwordForm = document.getElementById("passwordForm");
    const passwordMsg = document.getElementById("passwordMsg");
    const summaryName = document.getElementById("summaryName");
    const summaryEmail = document.getElementById("summaryEmail");
    const summaryPhone = document.getElementById("summaryPhone");
    const avatarBadge = document.getElementById("avatarBadge");

    function setStatus(element, message, tone = "") {
      element.textContent = message;
      element.className = `status-message ${tone}`.trim();
    }

    async function syncServerSession(user) {
      const idToken = await user.getIdToken();
      await fetch("../auth/session.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ id_token: idToken })
      });
    }

    async function hydrateProfile(user) {
      const userRef = doc(db, "users", user.uid);
      const snapshot = await getDoc(userRef);
      const profile = snapshot.exists() ? snapshot.data() : {};
      const displayName = profile.username || user.displayName || user.email || "Utilisateur";
      const phone = profile.phone || "";

      nomInput.value = displayName;
      emailInput.value = user.email || "";
      telInput.value = phone;
      summaryName.textContent = displayName;
      summaryEmail.textContent = user.email || "Non renseigne";
      summaryPhone.textContent = phone || "A completer";
      avatarBadge.textContent = (displayName.trim().charAt(0) || "U").toUpperCase();
    }

    onAuthStateChanged(auth, async (user) => {
      if (!user) {
        return;
      }

      if (currentUserUid !== user.uid) {
        await syncServerSession(user);
        window.location.reload();
        return;
      }

      if (infosForm) {
        await hydrateProfile(user);
      }
    });

    const infosForm = document.getElementById("infosForm");
    const passwordSectionForm = document.getElementById("passwordForm");

    if (infosForm) {
      infosForm.addEventListener("submit", async (event) => {
        event.preventDefault();
        setStatus(infosMsg, "");

      const user = auth.currentUser;
      if (!user) {
        setStatus(infosMsg, "Votre session a expire. Merci de vous reconnecter.", "error");
        return;
      }

      const displayName = nomInput.value.trim();
      const email = emailInput.value.trim();
      const phone = telInput.value.trim();

      try {
        if (displayName && displayName !== user.displayName) {
          await updateProfile(user, { displayName });
        }

        if (email && email !== user.email) {
          await updateEmail(user, email);
        }

        await setDoc(doc(db, "users", user.uid), {
          username: displayName || user.email || "Utilisateur",
          phone,
          email,
          updatedAt: serverTimestamp()
        }, { merge: true });

        await syncServerSession(user);
        await hydrateProfile(user);
        setStatus(infosMsg, "Informations mises a jour avec succes.", "success");
      } catch (error) {
        setStatus(infosMsg, `Erreur : ${error.message}`, "error");
      }
      });
    }

    if (passwordSectionForm) {
      passwordSectionForm.addEventListener("submit", async (event) => {
        event.preventDefault();
        setStatus(passwordMsg, "");

        const user = auth.currentUser;
        if (!user) {
          setStatus(passwordMsg, "Votre session a expire. Merci de vous reconnecter.", "error");
          return;
        }

        const currentPassword = document.getElementById("currentPassword").value;
        const newPassword = document.getElementById("newPassword").value;
        const confirmPassword = document.getElementById("confirmPassword").value;

        if (newPassword !== confirmPassword) {
          setStatus(passwordMsg, "Les mots de passe ne correspondent pas.", "error");
          return;
        }

        try {
          const credential = EmailAuthProvider.credential(user.email, currentPassword);
          await reauthenticateWithCredential(user, credential);
          await updatePassword(user, newPassword);
          passwordSectionForm.reset();
          setStatus(passwordMsg, "Mot de passe modifie avec succes.", "success");
        } catch (error) {
          setStatus(passwordMsg, `Erreur : ${error.message}`, "error");
        }
      });
    }
  </script>
  <script src="../scripts/app-ui.js"></script>
</body>
</html>
