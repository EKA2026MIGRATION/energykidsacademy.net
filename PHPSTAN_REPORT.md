# Rapport PHPStan — EnergyAcademyClient

## Chiffres clés

- **2 erreurs**, gelées dans `phpstan-baseline.neon`.
- **Niveau utilisé : 0** (le plus bas), même raison que sur `appli-v` : framework MVC maison sans type-hints natifs systématiques. Trajectoire suggérée : monter progressivement (1 → 2 → ...) au fil d'un futur chantier de typage.
- Scope : `controller/`, `application/`, `view/render/`, `view/template/`.
- `vendor/bin/phpstan analyse` est **vert** — seules les erreurs **nouvelles** feront échouer une future exécution.

## Bootstrap d'analyse (`.phpstan/bootstrap.php`)

Même framework maison qu'`appli-v` : pas d'autoloading PSR-4, fonctions globales chargées à la demande via `use_helper()`, constantes définies au runtime par `MyConfiguration::start()` (appels réseau, session, redirections `exit()` — volontairement non reproduits ici). Le bootstrap définit les constantes avec des valeurs factices et charge tous les fichiers de `application/helper/` d'un coup.

## Bibliothèques vendored hors périmètre

`php-library/twilio-php-master/` (code tiers) est déclaré en `scanDirectories` pour que PHPStan résolve `Twilio\Rest\Client` sans l'analyser. PHPMailer (`vendor/phpmailer/`) est un vrai paquet Composer, résolu automatiquement via `vendor/autoload.php`, pas de configuration supplémentaire nécessaire.

## Les 2 erreurs restantes — code mort

`application/helper/buttons.php` (`showFloatingActionButton()`) et `application/helper/dates.php` (`showDatePickerNavigation()`) font chacune un `include()` vers `view/render/blockTemplate/...` — un dossier qui n'existe pas dans ce repo. **Aucun appelant trouvé nulle part** pour ces deux fonctions (`grep` sur `controller/` et `view/` : zéro résultat) — code mort, jamais exécuté en pratique. Si l'une de ces fonctions est un jour réutilisée, le fichier `blockTemplate` correspondant devra être reconstitué ou l'appel retiré.

## Fichiers les plus concernés

Sans objet — seulement 2 erreurs, toutes deux détaillées ci-dessus.

## Comment l'utiliser

```bash
# Lancer l'analyse (doit rester vert)
php8.3 vendor/bin/phpstan analyse

# Après un chantier de fix, régénérer le baseline pour capturer la réduction
php8.3 vendor/bin/phpstan analyse --generate-baseline=phpstan-baseline.neon
```