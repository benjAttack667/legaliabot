<?php

require_once __DIR__ . '/../bootstrap.php';

$user = current_user();
$chatMessages = $user ? load_chat_history($user['uid']) : [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['question'])) {
    if (!$user) {
        json_response([
            'ok' => false,
            'error' => 'Votre session a expire. Merci de vous reconnecter.',
        ], 401);
    }

    $question = trim((string) ($_POST['question'] ?? ''));
    $chatConfig = app_config()['chat'];

    if ($question === '') {
        json_response([
            'ok' => false,
            'error' => 'Merci de saisir une question.',
        ], 422);
    }

    if (strlen($question) > $chatConfig['maxQuestionLength']) {
        json_response([
            'ok' => false,
            'error' => 'Votre message est trop long. Merci de le raccourcir avant de l envoyer.',
        ], 422);
    }

    if (is_rate_limited('chat', user_request_identifier(), $chatConfig['rateLimitPerMinute'], 60)) {
        json_response([
            'ok' => false,
            'error' => 'Vous envoyez des messages trop vite. Merci de patienter une minute puis de reessayer.',
        ], 429);
    }

    $moderation = moderate_openai_input($question);

    if (!empty($moderation['flagged'])) {
        json_response([
            'ok' => false,
            'error' => 'Votre message ne peut pas etre traite en l etat. Merci de reformuler votre demande de facon plus sobre et non sensible.',
        ], 400);
    }

    $answer = openai_response_for_question($question);
    append_chat_history($user['uid'], $question, $answer);

    json_response([
        'ok' => true,
        'question' => h($question),
        'reponse' => nl2br(h($answer)),
        'firebase_name' => h($user['name']),
    ]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Chat juridique - LegaliaBot</title>
  <link rel="stylesheet" href="../styles/style.css" />
</head>
<body data-user-uid="<?= h($user['uid'] ?? '') ?>">
  <div class="container">
    <?php include '../sidebar.php'; ?>

    <main class="content">
      <section class="page-hero">
        <h1>Chat juridique</h1>
        <p>Posez vos questions en langage simple, retrouvez vos echanges et poursuivez la conversation sur mobile comme sur desktop.</p>
      </section>

      <?php if (!$user): ?>
        <section class="panel auth-notice">
          <h2>Connexion requise</h2>
          <p>Connectez-vous avec votre compte Firebase pour ouvrir votre session serveur, enregistrer votre historique et utiliser le chatbot.</p>
          <div class="button-row">
            <a class="btn-primary" href="../auth/authentification.php">Se connecter</a>
            <a class="btn-secondary" href="../auth/connexion.php">Creer un compte</a>
          </div>
        </section>
      <?php else: ?>
        <div class="search-bar">
          <input type="text" value="Bonjour <?= h($user['name']) ?>, posez votre question juridique ici." readonly />
        </div>

        <section class="chat-box" id="chat-box" style="overflow-y:auto; max-height: 70vh; padding: 30px; display: flex; flex-direction: column; gap: 20px;">
          <?php if (empty($chatMessages)): ?>
            <div class="bot-message"><strong>LegaliaBot :</strong> Je suis pret. Decrivez votre situation ou votre question.</div>
          <?php endif; ?>

          <?php foreach ($chatMessages as $message): ?>
            <div class="user-message-wrapper">
              <div class="user-label"><?= h($user['name']) ?></div>
              <div class="user-message">
                <?= h($message['question']) ?>
                <img src="https://www.w3schools.com/howto/img_avatar2.png" class="avatar" alt="Avatar utilisateur" />
              </div>
            </div>

            <div class="message-wrapper">
              <img src="https://www.w3schools.com/howto/img_avatar.png" class="avatar" alt="Avatar chatbot" />
              <div>
                <div class="sender-name">LegaliaBot</div>
                <div class="bot-message"><?= nl2br(h($message['reponse'])) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </section>

        <section class="chat-input">
          <form method="POST" id="chat-form" style="display:flex; gap:12px; width:100%; align-items:center;">
            <input type="text" name="question" placeholder="Exemple : puis-je contester un licenciement abusif ?" required autocomplete="off" />
            <button type="submit">Envoyer</button>
          </form>
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

    const form = document.getElementById("chat-form");

    if (form) {
      form.addEventListener("submit", async (event) => {
        event.preventDefault();

        const response = await fetch(window.location.href, {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: new URLSearchParams(new FormData(form))
        });

        const result = await response.json();

        if (!result.ok) {
          alert(result.error || "Impossible d'envoyer la question.");
          return;
        }

        const chatBox = document.getElementById("chat-box");

        const userWrapper = document.createElement("div");
        userWrapper.className = "user-message-wrapper";
        userWrapper.innerHTML = `
          <div class="user-label">${result.firebase_name}</div>
          <div class="user-message">
            ${result.question}
            <img src="https://www.w3schools.com/howto/img_avatar2.png" class="avatar" alt="Avatar utilisateur" />
          </div>`;

        const botWrapper = document.createElement("div");
        botWrapper.className = "message-wrapper";
        botWrapper.innerHTML = `
          <img src="https://www.w3schools.com/howto/img_avatar.png" class="avatar" alt="Avatar chatbot" />
          <div>
            <div class="sender-name">LegaliaBot</div>
            <div class="bot-message">${result.reponse}</div>
          </div>`;

        chatBox.appendChild(userWrapper);
        chatBox.appendChild(botWrapper);
        chatBox.scrollTop = chatBox.scrollHeight;
        form.reset();
      });
    }
  </script>
  <script src="../scripts/app-ui.js"></script>
</body>
</html>
