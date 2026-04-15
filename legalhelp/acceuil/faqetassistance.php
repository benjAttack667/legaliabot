<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ et Assistance - Legaliabot</title>
    <link rel="stylesheet" href="../styles/faqetassistance.css">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <div class="logo">⚖️ Legaliabot</div>
            <ul class="nav-links">
                <li><a href="accueil.php">🏠 Accueil</a></li>
                  <li><a href="../chat_bot/pas.php">🤖 Chatbot</a></li>
                <li><a href="../information_personnel/compte.php">👤 Mon Compte</a></li>
                <li><a href="../auth/deconnexion.php">🚪 Déconnexion</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="header">
            <h1>FAQ et Assistance</h1>
            <p>Trouvez rapidement des réponses à vos questions juridiques les plus fréquentes</p>
        </div>

        <div class="faq-section">
            <!-- Droit du Travail -->
            <div class="faq-item travail">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-title">
                        <div class="faq-icon">⚒️</div>
                        <span>Quels sont mes droits en cas de licenciement abusif ?</span>
                    </div>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>En cas de licenciement abusif, vous avez droit à des indemnités compensatrices. Celles-ci incluent généralement l'indemnité de licenciement, l'indemnité compensatrice de préavis, et potentiellement des dommages-intérêts. Il est recommandé de consulter un avocat spécialisé en droit du travail et de saisir le conseil de prud'hommes dans un délai de 12 mois suivant la rupture du contrat.</p>
                </div>
            </div>

            <!-- Droit des Affaires -->
            <div class="faq-item affaires">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-title">
                        <div class="faq-icon">💼</div>
                        <span>Comment protéger ma propriété intellectuelle en entreprise ?</span>
                    </div>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Pour protéger votre propriété intellectuelle, déposez vos marques auprès de l'INPI, protégez vos créations par le droit d'auteur, et envisagez des brevets pour vos innovations techniques. Établissez des accords de confidentialité avec vos employés et partenaires, et documentez vos créations avec des dates certaines.</p>
                </div>
            </div>

            <!-- Droit des Étrangers -->
            <div class="faq-item etrangers">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-title">
                        <div class="faq-icon">🌍</div>
                        <span>Quelles démarches pour obtenir un titre de séjour en France ?</span>
                    </div>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>La demande de titre de séjour doit être effectuée en préfecture dans les 2 mois suivant votre arrivée en France. Vous devrez fournir un passeport valide, un justificatif de domicile, des photos d'identité, et des documents spécifiques selon votre situation (contrat de travail, certificat de scolarité, etc.). Les délais et conditions varient selon le motif de séjour.</p>
                </div>
            </div>

            <!-- Droit de la Famille -->
            <div class="faq-item famille">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-title">
                        <div class="faq-icon">👨‍👩‍👧‍👦</div>
                        <span>Comment se déroule une procédure de divorce par consentement mutuel ?</span>
                    </div>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Le divorce par consentement mutuel ne nécessite plus de passage devant le juge depuis 2017. Chaque époux doit avoir son propre avocat. La convention de divorce, signée par les parties et contresignée par les avocats, doit être déposée chez un notaire dans les 7 jours. La procédure dure généralement entre 1 et 3 mois et coûte moins cher qu'un divorce contentieux.</p>
                </div>
            </div>

            <!-- Droit de l'Immobilier -->
            <div class="faq-item immobilier">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-title">
                        <div class="faq-icon">🏠</div>
                        <span>Quels sont mes recours face à un vice caché immobilier ?</span>
                    </div>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>En cas de vice caché, vous disposez de 2 ans à compter de la découverte pour agir. Vous pouvez demander l'annulation de la vente avec restitution du prix (action rédhibitoire) ou une diminution du prix (action estimatoire). Le vice doit être antérieur à la vente, caché lors de l'acquisition, et suffisamment grave pour rendre le bien impropre à sa destination.</p>
                </div>
            </div>

            <!-- Droit de la Consommation -->
            <div class="faq-item consommation">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-title">
                        <div class="faq-icon">🛒</div>
                        <span>Comment exercer mon droit de rétractation sur un achat en ligne ?</span>
                    </div>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Vous disposez de 14 jours calendaires pour vous rétracter d'un achat en ligne, sans avoir à justifier votre décision. Le délai court à partir de la réception du bien. Vous devez informer le vendeur par écrit (email, courrier, formulaire). Les frais de retour sont généralement à votre charge, sauf mention contraire. Le remboursement doit intervenir dans les 14 jours suivant la rétractation.</p>
                </div>
            </div>

            <!-- Droit Administratif -->
            <div class="faq-item administration">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <div class="faq-title">
                        <div class="faq-icon">🏛️</div>
                        <span>Comment contester une décision administrative défavorable ?</span>
                    </div>
                    <span class="toggle-icon">+</span>
                </div>
                <div class="faq-answer">
                    <p>Vous pouvez d'abord exercer un recours gracieux auprès de l'administration concernée dans les 2 mois suivant la notification. En l'absence de réponse ou en cas de rejet, vous disposez de 2 mois pour saisir le tribunal administratif compétent. Il est conseillé de se faire assister par un avocat spécialisé en droit public pour maximiser vos chances de succès.</p>
                </div>
            </div>
        </div>

        <div class="assistance-section">
            <h2>🤝 Besoin d'aide supplémentaire ?</h2>
            <div class="contact-info">
                <div class="contact-card">
                    <div class="contact-icon">🤖</div>
                    <h3>Assistant IA 24/7</h3>
                    <p>Notre chatbot juridique est disponible en permanence pour répondre à vos questions.</p>
                </div>
                <div class="contact-card">
                    <div class="contact-icon">📧</div>
                    <h3>Support Email</h3>
                    <p>Contactez notre équipe à<br><strong>support@legaliabot.fr</strong></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFaq(element) {
            const faqItem = element.parentElement;
            const answer = faqItem.querySelector('.faq-answer');
            const isActive = faqItem.classList.contains('active');
            
            // Fermer toutes les autres FAQ
            document.querySelectorAll('.faq-item.active').forEach(item => {
                if (item !== faqItem) {
                    item.classList.remove('active');
                    item.querySelector('.faq-answer').classList.remove('active');
                }
            });
            
            // Toggle la FAQ actuelle
            if (isActive) {
                faqItem.classList.remove('active');
                answer.classList.remove('active');
            } else {
                faqItem.classList.add('active');
                answer.classList.add('active');
            }
        }

        // Animation d'entrée progressive
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    item.style.transition = 'all 0.6s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>
