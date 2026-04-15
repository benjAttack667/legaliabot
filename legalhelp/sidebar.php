<?php $activePage = basename($_SERVER['PHP_SELF'] ?? ''); ?>
<button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="app-sidebar" aria-expanded="false">
  Menu
</button>
<div class="sidebar-backdrop"></div>
<aside class="sidebar" id="app-sidebar">
  <div class="sidebar-header">
    <div>
      <h2>LegaliaBot</h2>
      <p>Votre assistant juridique IA</p>
    </div>
    <button class="sidebar-close" type="button" data-sidebar-close aria-label="Fermer le menu">
      ×
    </button>
  </div>

  <nav class="sidebar-nav" aria-label="Navigation principale">
    <a class="sidebar-link <?= $activePage === 'accueil.php' ? 'active' : '' ?>" href="../acceuil/accueil.php">Accueil</a>
    <a class="sidebar-link <?= $activePage === 'compte.php' ? 'active' : '' ?>" href="../information_personnel/compte.php">Mon compte</a>
    <a class="sidebar-link <?= $activePage === 'pas.php' ? 'active' : '' ?>" href="../chat_bot/pas.php">ChatBot</a>
    <a class="sidebar-link <?= $activePage === 'historique.php' ? 'active' : '' ?>" href="../chat_bot/historique.php">Historique</a>
    <a class="sidebar-link" href="../information_personnel/contact.php">Contact</a>
    <a class="sidebar-link" href="../auth/deconnexion.php">Deconnexion</a>
  </nav>

  <button id="toggle-mode" class="mode-toggle" type="button" aria-pressed="false">
    Mode sombre
  </button>
</aside>
