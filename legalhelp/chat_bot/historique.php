<?php

require_once __DIR__ . '/../bootstrap.php';

$user = current_user();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['supprimer']) && $user) {
    clear_chat_history($user['uid']);
    header('Location: historique.php');
    exit();
}

$history = $user ? load_chat_history($user['uid']) : [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Historique - LegaliaBot</title>
  <link rel="stylesheet" href="../styles/style.css" />
  <style>
    .history-card {
      padding: clamp(20px, 4vw, 32px);
    }

    .history-list {
      display: grid;
      gap: 16px;
    }

    .history-question {
      width: 100%;
      border: none;
      border-radius: 18px;
      padding: 18px 20px;
      text-align: left;
      background: linear-gradient(135deg, rgba(29, 95, 145, 0.1), rgba(103, 180, 199, 0.16));
      color: var(--text);
      font: inherit;
      font-weight: 700;
      cursor: pointer;
    }

    .history-answer {
      display: none;
      margin-top: -4px;
      border: 1px solid var(--border);
      border-radius: 18px;
      padding: 18px 20px;
      background: rgba(255, 255, 255, 0.8);
      color: var(--text);
      line-height: 1.7;
    }

    .history-answer.is-open {
      display: block;
    }
  </style>
</head>
<body data-user-uid="<?= h($user['uid'] ?? '') ?>">
  <div class="container">
    <?php include '../sidebar.php'; ?>

    <main class="content">
      <section class="page-hero">
        <h1>Historique</h1>
        <p>Retrouvez vos questions precedentes, supprimez votre historique si besoin et reprenez rapidement vos echanges.</p>
      </section>

      <?php if (!$user): ?>
        <section class="panel auth-notice">
          <h2>Connexion requise</h2>
          <p>Votre historique est rattache a votre session. Connectez-vous pour afficher vos anciens messages.</p>
          <div class="button-row">
            <a class="btn-primary" href="../auth/authentification.php">Se connecter</a>
            <a class="btn-secondary" href="../auth/connexion.php">Creer un compte</a>
          </div>
        </section>
      <?php else: ?>
        <section class="panel history-card">
          <form method="POST" style="display:flex; justify-content:flex-end; margin-bottom:20px;">
            <button type="submit" name="supprimer" class="btn-danger">Supprimer tout l'historique</button>
          </form>

          <?php if (empty($history)): ?>
            <div class="empty-state">
              <h2>Aucun message enregistre</h2>
              <p>Votre historique apparaitra ici apres votre premiere conversation avec le chatbot.</p>
              <div class="button-row">
                <a class="btn-primary" href="../chat_bot/pas.php">Ouvrir le chat</a>
              </div>
            </div>
          <?php else: ?>
            <div class="history-list">
              <?php foreach ($history as $index => $message): ?>
                <div>
                  <button class="history-question" type="button" data-history-toggle="<?= (int) $index ?>">
                    Question <?= (int) $index + 1 ?> : <?= h($message['question']) ?>
                  </button>
                  <div class="history-answer" id="history-answer-<?= (int) $index ?>">
                    <?= nl2br(h($message['reponse'])) ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </section>
      <?php endif; ?>
    </main>
  </div>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-app.js";
    import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/11.9.1/firebase-auth.js";

    const firebaseConfig = <?= firebase_web_config_json() ?>;
    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const currentUserUid = document.body.dataset.userUid || "";

    async function syncServerSession(user) {
      const idToken = await user.getIdToken();
      const response = await fetch("../auth/session.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ id_token: idToken })
      });

      return response.json();
    }

    onAuthStateChanged(auth, async (firebaseUser) => {
      if (firebaseUser && currentUserUid !== firebaseUser.uid) {
        const result = await syncServerSession(firebaseUser);
        if (result.ok) {
          window.location.reload();
        }
      }
    });
  </script>
  <script>
    document.querySelectorAll("[data-history-toggle]").forEach((button) => {
      button.addEventListener("click", () => {
        const target = document.getElementById(`history-answer-${button.dataset.historyToggle}`);
        target.classList.toggle("is-open");
      });
    });
  </script>
  <script src="../scripts/app-ui.js"></script>
</body>
</html>
