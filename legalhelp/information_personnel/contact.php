<?php

require_once __DIR__ . '/../bootstrap.php';

$errors = [];
$successMessage = '';
$formValues = [
    'nom' => '',
    'email' => '',
    'sujet' => '',
    'message' => '',
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $formValues = [
        'nom' => trim((string) ($_POST['nom'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'sujet' => trim((string) ($_POST['sujet'] ?? '')),
        'message' => trim((string) ($_POST['message'] ?? '')),
    ];

    if ($formValues['nom'] === '') {
        $errors[] = 'Le nom est obligatoire.';
    }

    if (!filter_var($formValues['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Merci de renseigner une adresse e-mail valide.';
    }

    if ($formValues['sujet'] === '') {
        $errors[] = 'Le sujet est obligatoire.';
    }

    if ($formValues['message'] === '' || strlen($formValues['message']) < 10) {
        $errors[] = 'Votre message doit contenir au moins 10 caracteres.';
    }

    if (empty($errors)) {
        save_contact_message([
            'name' => $formValues['nom'],
            'email' => $formValues['email'],
            'subject' => $formValues['sujet'],
            'message' => $formValues['message'],
            'created_at' => date('c'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        $successMessage = 'Votre message a bien ete enregistre. Nous reviendrons vers vous rapidement.';
        $formValues = ['nom' => '', 'email' => '', 'sujet' => '', 'message' => ''];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - LegaliaBot</title>
  <link rel="stylesheet" href="../styles/contact.css">
</head>
<body>
  <main class="contact-page">
    <section class="contact-shell">
      <div class="contact-header">
        <a class="back-link" href="../acceuil/accueil.php">Retour a l'accueil</a>
        <h1>Contactez-nous</h1>
        <p>Une question sur la plateforme, un bug ou un besoin d'accompagnement ? Laissez-nous un message.</p>
      </div>

      <?php if (!empty($errors)): ?>
        <div class="feedback error">
          <?= h(implode(' ', $errors)) ?>
        </div>
      <?php endif; ?>

      <?php if ($successMessage !== ''): ?>
        <div class="feedback success">
          <?= h($successMessage) ?>
        </div>
      <?php endif; ?>

      <div class="contact-grid">
        <form class="contact-form" method="POST">
          <label for="nom">Nom</label>
          <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" value="<?= h($formValues['nom']) ?>" required>

          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Entrez votre email" value="<?= h($formValues['email']) ?>" required>

          <label for="sujet">Sujet</label>
          <input type="text" id="sujet" name="sujet" placeholder="Entrez votre sujet" value="<?= h($formValues['sujet']) ?>" required>

          <label for="message">Message</label>
          <textarea id="message" name="message" rows="6" placeholder="Votre message" required><?= h($formValues['message']) ?></textarea>

          <button type="submit">Envoyer</button>
        </form>

        <aside class="contact-infos">
          <div class="info-block">
            <span class="icon">Email</span>
            <div>
              <strong>Support</strong>
              <span>support@legaliabot.fr</span>
            </div>
          </div>

          <div class="info-block">
            <span class="icon">Tel</span>
            <div>
              <strong>Assistance</strong>
              <span>+33 1 23 45 67 89</span>
            </div>
          </div>

          <div class="info-block">
            <span class="icon">FAQ</span>
            <div>
              <strong>Centre d'aide</strong>
              <span>Consultez aussi la FAQ et l'assistance pour les questions recurrentes.</span>
            </div>
          </div>
        </aside>
      </div>
    </section>
  </main>
</body>
</html>
