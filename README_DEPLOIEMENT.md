# Deploiement LegaliaBot

## Structure recommandee
- Document root recommande : le dossier `legalhelp/`
- Si votre hebergeur pointe le document root sur la racine du projet, `index.php` redirige deja vers `legalhelp/acceuil/index.php`
- Les donnees locales sont stockees dans `storage/`, a conserver en ecriture

## Avant la mise en ligne
1. Copier `.env.example` vers `.env`
2. Renseigner `OPENAI_API_KEY`
3. Verifier que votre domaine est ajoute dans les domaines autorises de Firebase Authentication
4. S'assurer que PHP 8.1+ est disponible
5. S'assurer que le dossier `storage/` est accessible en ecriture par le serveur
6. Pointer si possible le document root vers `legalhelp/`

## Variables d'environnement
- `APP_ENV=production`
- `OPENAI_API_KEY=...`
- `OPENAI_MODEL=gpt-4o-mini`
- Les variables Firebase peuvent rester telles quelles si vous utilisez le meme projet Firebase

## Verification rapide
- Page d'accueil : `/acceuil/index.php`
- Sante applicative : `/health.php`
- Chat : `/chat_bot/pas.php`
- Compte : `/information_personnel/compte.php`

## Firebase Authentication
- Ouvrir Firebase Console
- Aller dans `Authentication` puis `Settings`
- Dans `Authorized domains`, ajouter votre domaine final
- Exemples : `monsite.com`, `www.monsite.com`
- Si vous testez avant le DNS final, ajouter aussi le sous-domaine temporaire de l'hebergeur
- Voir aussi `deploy/FIREBASE_OPENAI_SETUP.md`

## Document root
- Recommande : faire pointer le document root directement vers le dossier `legalhelp/`
- Si ce n'est pas possible, la racine du projet contient deja un `index.php` qui redirige vers `legalhelp/acceuil/index.php`
- Apache/cPanel : choisir `.../legalhelp2/legalhelp` comme `DocumentRoot` si l'interface le permet
- MAMP local : utiliser `http://localhost:8888/legalhelp/`
- Si votre hebergeur impose la racine du projet comme dossier public, garder le fichier `index.php` a la racine

## Fichiers utiles
- Regles Firestore : `deploy/firestore.rules`
- Setup Firebase/OpenAI : `deploy/FIREBASE_OPENAI_SETUP.md`
- Exemple Apache : `deploy/apache-vhost.example.conf`

## Tests Robot Framework
- Lancer `powershell -ExecutionPolicy Bypass -File tests\robot\run.ps1`
- Rapport HTML : `tests\robot\results\report.html`

## Notes de securite
- La cle OpenAI ne doit jamais etre committee dans le code
- Le fichier `.env` ne doit pas etre publie
- Les regles `.htaccess` ajoutent des protections de base pour Apache

## Stockage
- Historique chat : `storage/chat_history/*.json`
- Messages contact : `storage/contact/messages.log`

## Si vous deployez sur Apache/cPanel
- Garder les fichiers `.htaccess`
- Preferer `legalhelp/` comme document root
- Si possible, laisser `storage/` hors du dossier public
