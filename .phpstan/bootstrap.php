<?php

/**
 * PHPStan bootstrap for EnergyAcademyClient.
 *
 * Same custom framework as appli-v: no PSR-4 autoloading (classes resolved by
 * filename via spl_autoload_register in MyConfiguration::autoload()), and
 * global helper functions loaded on demand via use_helper() rather than at
 * startup. This does NOT reproduce MyConfiguration::start() (live cURL calls
 * to the API, session handling, redirects with exit()) — it only defines the
 * constants and loads the global functions that would exist at runtime, so
 * PHPStan can resolve them instead of reporting them as unknown.
 */

define('ROOT', __DIR__ . '/../');
define('HELPER', ROOT . 'application/helper/');
define('CONTROLLER', ROOT . 'controller/');
define('VIEW', ROOT . 'view/');
define('APPLICATION', ROOT . 'application/');
define('MODEL', ROOT . 'model/');

define('HOST', 'http://eka-client/');
define('API', 'https://api.appli-v.net/');
define('URL_PHOTO', 'https://appli-v.net/');

define('ASSETS', HOST . 'assets/');
define('JS', ASSETS . 'js/');
define('CSS', ASSETS . 'css/');
define('IMG', ASSETS . 'image/');

define('ROUTE_FIRST_ELEMENT', '');
define('FILES_ROUTE', []);
define('SIZE_RECHERCHE', 20);
define('SIZE_LIST', 50);

define('TWILIO_ID', '');
define('TWILIO_TOKEN', '');
define('MAPS_API_KEY', '');

define('PAYMENT_ADMIN_EMAIL', '');
define('PAYMENT_ADMIN_PASSWORD', '');
define('SYSTEMPAY_PROD_KEY', '');
define('SYSTEMPAY_TEST_KEY', '');
define('PAYMENT_SMTP_USERNAME', '');
define('PAYMENT_SMTP_PASSWORD', '');
define('MAIL_SMTP_USERNAME', '');
define('MAIL_SMTP_PASSWORD', '');
define('AES_SECRET_KEY', '');
define('AES_SECRET_IV', '');
define('SSO_PASSPHRASE', '');
define('DEBUG_EMAIL', '');

define('ROLE', 'NOTHING');
define('TOKEN', '');
define('PERSON_CONNECTED', []);

require_once APPLICATION . 'Functions.php';

foreach (glob(HELPER . '*.php') as $helperFile) {
    require_once $helperFile;
}
