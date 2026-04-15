# Firebase et OpenAI

## Firebase Authentication
1. Ouvrir Firebase Console
2. Aller dans `Authentication`
3. Onglet `Sign-in method`
4. Activer `Email/Password`
5. Aller dans `Settings`
6. Ajouter votre domaine dans `Authorized domains`

## Firestore
1. Ouvrir `Firestore Database`
2. Creer la base en mode production
3. Coller les regles du fichier `deploy/firestore.rules`
4. Publier les regles

## OpenAI
1. Ouvrir la plateforme OpenAI
2. Creer une cle API de projet
3. Ajouter la cle dans `.env` a la ligne `OPENAI_API_KEY`
4. Verifier la facturation et fixer une limite de budget
5. Garder la cle uniquement cote serveur

## Variables de prod minimales
- `APP_ENV=production`
- `OPENAI_API_KEY=...`
- `OPENAI_MODEL=gpt-4o-mini`
- `OPENAI_MODERATION_MODEL=omni-moderation-latest`
- `CHAT_MAX_QUESTION_LENGTH=1200`
- `CHAT_RATE_LIMIT_PER_MINUTE=12`
