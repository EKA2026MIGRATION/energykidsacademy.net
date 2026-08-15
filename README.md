# energykidsacademy.net

Site client d'Energy Kids Academy — espace famille et paiement en ligne (SystemPay/PayZen).

## Stack

- PHP 8.3, même framework MVC maison qu'[`appli-v`](https://github.com/EKA2026MIGRATION/appli-v) (`application/`, `controller/`, `view/`)
- Consomme l'API [`api-appli-v`](https://github.com/EKA2026MIGRATION/api-appli-v)

## Architecture — code legacy

Même socle qu'`appli-v` : pas de framework tiers, autoload par nom de classe via `spl_autoload_register` (`config/config.php`), pas de build ni d'étape d'installation. Certaines bibliothèques tierces (Twilio) sont embarquées directement dans `php-library/`, pas via Composer — seul PHPMailer est un vrai paquet Composer ici.

**Routage** : les routes sont déclarées dans `application/routes/routes.ini` (format INI, une section par route), sur le même modèle qu'`appli-v` :

```ini
[app/home]
controller = "Home"
method     = "display"
security   = "ALL"
js         = "..."
css        = "..."
```

`application/Routeur.php` lit ce fichier pour dispatcher chaque requête vers le bon contrôleur.

**Configuration** : `config/config.php` lit un fichier `.env` à la racine (format INI, via `parse_ini_file`) et le transforme en constantes PHP globales (`API`, `PHOTO_URL`, clés de paiement/SMTP...). Créer un `.env` à la racine — voir les clés attendues directement dans `config/config.php`.

## Sessions & authentification

Authentification par session PHP native (`$_SESSION`), même mécanisme qu'`appli-v` — le JWT existe côté `api-appli-v` pour les appels serveur-à-serveur, le front garde un token en session.

- **Cookie de session** : `httponly`, `secure` (si HTTPS), `samesite=Lax`, réglés dans `config/config.php` avant `session_start()`.
- **Connexion** (`Auth::checkAuth`, route `auth/check`) : le front envoie `token` + `userIdentifier` + `userRoles`. Avant d'établir la session, le contrôleur vérifie côté serveur que l'API valide bien ce token pour cet identifiant (`person/display/{userIdentifier}`) — si la réponse API échoue ou ne renvoie pas de `personId`, la session n'est jamais créée (401). Sans cette vérification, un token invalide/expiré aurait quand même établi une session avec l'identité et le rôle fournis par le client. `session_regenerate_id(true)` est appelé à la connexion.
- **Avant connexion** : `$_SESSION['TOKEN']` absent → `ROLE = 'NOTHING'`, `PERSON_CONNECTED = []` (évite qu'un code qui lit `PERSON_CONNECTED` sans garde ne tombe sur la constante non définie). Toute route hors whitelist redirige vers `auth/display` (avec `exit;`).
- **Contrôle d'accès par route** : les 40 routes de `routes.ini` sont toutes `security = "ALL"` — pas de granularité par rôle au niveau du routeur (contrairement à `appli-v`). Le seul filtre réel est la redirection + `exit` en l'absence de session valide.
- **Pont SSO** (`ea/connectApp` dans `config/config.php`) : même logique de validation serveur avant établissement de session que `checkAuth`.

## Déploiement

Pas de CI/CD : la mise en production se fait par copie manuelle du dossier applicatif sur le serveur (voir le dépôt `api-appli-v` pour un exemple de checklist de bascule, `CUTOVER.md`). Ne jamais écraser en prod : `.env`, `uploads/`, `assets/document/` (données propres à l'environnement, non versionnées ici).

Composer ne gère que PHPMailer et l'outillage de développement (PHPStan) :

```bash
composer install
```

## Qualité du code

Analyse statique via PHPStan (niveau 0, cf. `PHPSTAN_REPORT.md`) :

```bash
vendor/bin/phpstan analyse
```
