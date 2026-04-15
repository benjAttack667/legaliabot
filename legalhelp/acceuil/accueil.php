<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LegaliaBot - Assistant Juridique</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="../styles/accueil.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <i class="fas fa-balance-scale"></i>
                <h2>LegaliaBot</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="#accueil">Accueil</a></li>
                <li><a href="faqetassistance.php">FAQ & Assistance</a></li>
                <li><a href="../chat_bot/pas.php">chatbot</a></li>
                <li><a href="../information_personnel/contact.php">Contact</a></li>
                <li><a href="../auth/deconnexion.php">deconnexion</a></li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="accueil">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Votre Assistant Juridique Intelligent</h1>
                <p>Obtenez des conseils juridiques précis et instantanés avec LegaliaBot</p>
            </div>
        </div>
        
        <div class="floating-elements">
            <div class="floating-icon icon-1">
                <i class="fas fa-balance-scale"></i>
            </div>
            <div class="floating-icon icon-2">
                <i class="fas fa-gavel"></i>
            </div>
        </div>
    </section>

    <!-- Section Services -->
    <section class="services" id="services">
        <div class="container">
            <h2>Nos Domaines d'expertise</h2>
            
            <!-- Première ligne : 2 colonnes -->
            <div class="services-row-1">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3>Droit du Travail</h3>
                    <p>Contrats, licenciements, conflits employeur-employé</p>
                    <a href="../domaine/travail.php" class="detail-btn">Détail</a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Droit des Affaires</h3>
                    <p>Création d'entreprise, contrats commerciaux, fusions</p>
                    <a href="../domaine/affaire.php" class="detail-btn">Détail</a>
                </div>
            </div>
            
            <!-- Deuxième ligne : 3 colonnes -->
            <div class="services-row-2">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3>Droit de la Consommation</h3>
                    <p>Protection des consommateurs et litiges commerciaux</p>
                    <a href="../domaine/consommation.php" class="detail-btn">Détail</a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3>Droit de la Famille</h3>
                    <p>Divorce, garde d'enfants, succession</p>
                    <a href="../domaine/famille.php" class="detail-btn">Détail</a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-passport"></i>
                    </div>
                    <h3>Droit des Étrangers</h3>
                    <p>Immigration, naturalisation, permis de séjour</p>
                    <a href="../domaine/etrangers.php" class="detail-btn">Détail</a>
                </div>
            </div>
            
            <!-- Troisième ligne : 2 colonnes -->
            <div class="services-row-3">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3>Droit de l'Immobilier</h3>
                    <p>Achat, vente, location, copropriété</p>
                    <a href="../domaine/immobilier.php" class="detail-btn">Détail</a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <h3>Droit de l'Administration</h3>
                    <p>Contentieux administratif, fonction publique</p>
                    <a href="../domaine/administration.php" class="detail-btn">Détail</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Chatbot -->
    <section class="chatbot-section" id="chatbot">
        <div class="container">
            <div class="chatbot-content">
                <div class="chatbot-illustration">
                    <div class="bot-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="chat-bubbles">
                        <div class="chat-bubble bot">
                            <p>Bonjour ! Comment puis-je vous aider aujourd'hui ?</p>
                        </div>
                        <div class="chat-bubble user">
                            <p>J'ai une question sur le droit du travail</p>
                        </div>
                        <div class="chat-bubble bot">
                            <p>Parfait ! Je suis là pour vous aider avec vos questions juridiques.</p>
                        </div>
                    </div>
                </div>
                <div class="chatbot-info">
                    <h2>Assistance Juridique 24/7</h2>
                    <p>Notre assistant intelligent est disponible à tout moment pour répondre à vos questions juridiques. Obtenez des conseils précis et fiables instantanément.</p>
                    <ul>
                        <li><i class="fas fa-check"></i> Réponses instantanées</li>
                        <li><i class="fas fa-check"></i> Conseils personnalisés</li>
                        <li><i class="fas fa-check"></i> Base de données juridique complète</li>
                        <li><i class="fas fa-check"></i> Interface intuitive</li>
                    </ul>
                    <form action="../chat_bot/pas.php" method="get">
                      <button type="submit" class="chat-cta">Discuter maintenant</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-balance-scale"></i>
                        <h3>LegaliaBot</h3>
                    </div>
                    <p>Votre assistant juridique intelligent pour tous vos besoins légaux.</p>
                </div>
                
                <div class="footer-section">
                    <h4>Contact</h4>
                    <div class="contact-info">
                        <p><i class="fas fa-envelope"></i> contact@legaliabot.fr</p>
                        <p><i class="fas fa-phone"></i> 01 23 45 67 89</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 LegaliaBot. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        document.querySelectorAll('.nav-menu a').forEach(n => n.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
        }));

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
