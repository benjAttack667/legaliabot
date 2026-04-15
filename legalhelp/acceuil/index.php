<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Bienvenue sur LegaliaBot</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles/accueil.css">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f1faee;
    }

    .hero-landing {
      background: linear-gradient(to right, #1d3557, #457b9d);
      color: #fff;
      padding: 100px 20px;
      text-align: center;
    }
    .hero-landing h1 {
      font-size: 3em;
      margin-bottom: 20px;
    }
    .hero-landing p {
      font-size: 1.3em;
      margin-bottom: 30px;
    }
    .cta-buttons {
      margin-top: 30px;
    }
    .cta-buttons a {
      display: inline-block;
      padding: 15px 30px;
      margin: 10px;
      border-radius: 30px;
      background-color: #f1faee;
      color: #1d3557;
      font-weight: bold;
      text-decoration: none;
      transition: background 0.3s;
    }
    .cta-buttons a:hover {
      background-color: #a8dadc;
    }

    .features, .guide {
      padding: 60px 20px;
      text-align: center;
      background-color: #ffffff;
    }
    .features h2, .guide h2 {
      font-size: 2em;
      margin-bottom: 40px;
      color: #1d3557;
    }
    .features-grid, .guide-steps {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
    }
    .feature, .step {
      background: #f8f9fa;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      max-width: 300px;
      text-align: center;
    }
    .feature i, .step i {
      font-size: 2.5em;
      color: #457b9d;
      margin-bottom: 15px;
    }
    .feature h3, .step h3 {
      color: #1d3557;
      margin-bottom: 10px;
    }

    footer {
      background-color: #1d3557;
      color: white;
      text-align: center;
      padding: 20px;
    }

    @media screen and (max-width: 768px) {
      .hero-landing h1 {
        font-size: 2em;
      }
      .features-grid, .guide-steps {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body>

  <!-- HERO -->
  <section class="hero-landing">
    <h1>Bienvenue sur LegaliaBot</h1>
    <p>Votre assistant juridique intelligent, accessible 24h/24 et 7j/7</p>
    <div class="cta-buttons">
      <a href="../auth/authentification.php"><i class="fas fa-sign-in-alt"></i> Connexion</a>
      <a href="../auth/connexion.php"><i class="fas fa-user-plus"></i> Créer un compte</a>
    </div>
  </section>

  <!-- FONCTIONNALITÉS -->
  <section class="features">
    <h2>Pourquoi choisir LegaliaBot ?</h2>
    <div class="features-grid">
      <div class="feature">
        <i class="fas fa-robot"></i>
        <h3>Réponses instantanées</h3>
        <p>Posez vos questions juridiques et recevez des réponses immédiatement grâce à l’IA.</p>
      </div>
      <div class="feature">
        <i class="fas fa-shield-alt"></i>
        <h3>Fiabilité</h3>
        <p>Basé sur les textes officiels comme Légifrance pour des réponses précises.</p>
      </div>
      <div class="feature">
        <i class="fas fa-clock"></i>
        <h3>Accessible 24h/24</h3>
        <p>Posez vos questions à tout moment, notre assistant est toujours là.</p>
      </div>
      <div class="feature">
        <i class="fas fa-thumbs-up"></i>
        <h3>Simple & Gratuit</h3>
        <p>Aucune connaissance en droit nécessaire pour utiliser notre assistant.</p>
      </div>
    </div>
  </section>

  <!-- GUIDE D'UTILISATION -->
  <section class="guide">
    <h2>Comment utiliser LegaliaBot ?</h2>
    <div class="guide-steps">
      <div class="step">
        <i class="fas fa-user-plus"></i>
        <h3>1. Inscription</h3>
        <p>Créez un compte gratuitement en quelques clics.</p>
      </div>
      <div class="step">
        <i class="fas fa-sign-in-alt"></i>
        <h3>2. Connexion</h3>
        <p>Connectez-vous avec votre email pour accéder au service.</p>
      </div>
      <div class="step">
        <i class="fas fa-comments"></i>
        <h3>3. Posez votre question</h3>
        <p>Accédez au chatbot et posez votre question juridique.</p>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <p>&copy; 2025 LegaliaBot - Simplifiez vos démarches juridiques.</p>
  </footer>

</body>
</html>
