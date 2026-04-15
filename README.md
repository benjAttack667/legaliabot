# LegaliaBot

LegaliaBot est une application PHP/Firebase avec un assistant juridique connecte a OpenAI.

## Fonctionnalites
- Inscription et connexion avec Firebase Authentication
- Page compte utilisateur synchronisee avec Firestore
- Chatbot juridique avec historique local serveur
- Formulaire de contact
- Interface responsive desktop/mobile
- Tests Robot Framework pour les parcours principaux

## Configuration
1. Copier `.env.production.example` vers `.env`
2. Renseigner `OPENAI_API_KEY`
3. Activer `Email/Password` dans Firebase Authentication
4. Ajouter le domaine public dans Firebase `Authorized domains`
5. Creer Firestore et publier les regles de `deploy/firestore.rules`

## Lancement local
```powershell
powershell -ExecutionPolicy Bypass -File .\tests\robot\run.ps1
```

## Deploiement
Voir `README_DEPLOIEMENT.md`.

## Securite
Ne jamais commiter `.env`, les historiques utilisateurs ou les logs de contact.
