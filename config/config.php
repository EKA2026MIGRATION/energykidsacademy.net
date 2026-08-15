<?php
class MyConfiguration
{
    /**
     * load the application
     */
    public static function start($url)
    {
        // errors — only show them on local dev, never in prod
        $isLocalDev = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);
        ini_set('display_errors', $isLocalDev ? 'on' : 'off');
        ini_set('log_errors', 'on');
        error_reporting(E_ALL ^ E_DEPRECATED);

        // start session
        session_set_cookie_params([
            'httponly' => true,
            'secure'   => !$isLocalDev,
            'samesite' => 'Lax',
        ]);
        session_start();

        // set time
        ini_set('date.timezone', 'Europe/Paris');

        // start autoload
        spl_autoload_register(array(__CLASS__, 'autoload'));
        MyConfiguration::initParameters($url);

        require_once(APPLICATION.'Functions.php');

    }
    /**
     * create all parameters
     */
    private static function initParameters($url)
    {
        $root = $_SERVER['DOCUMENT_ROOT'];
        $host = $_SERVER['HTTP_HOST'];
        // load config from .env (single local config file, gitignored) — same
        // mechanism as appli-v, replacing the old tracked config/config.ini.
        $envConfig = __DIR__.'/../.env';
        $parameters = is_file($envConfig) ? parse_ini_file($envConfig) : array();
        $parameters_route = parse_ini_file('application/routes/routes.ini', true);        
        $elements = explode('/', $url);
        // set parameters
        $whitelist = array(
            '127.0.0.1',
            '::1'
        );

        if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            $beforeHost = "http://";
        }
        else
        {
            $beforeHost = "https://";
        }


        if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            $beforeHost = "http://";
            define('HOST', $beforeHost.$host.$parameters['folder_app_dev'].'/');
            define('ROOT', $root.$parameters['folder_app_dev'].'/');


        }
        else
        {
            $beforeHost = "https://";
            define('HOST', $beforeHost.$host.$parameters['folder_app_prod'].'/');
            define('ROOT', $root.$parameters['folder_app_prod'].'/');
            
        }
        define('API', $parameters['api_call']);
        define('URL_PHOTO', $parameters['photo_call']);
        define('PAYMENT_ADMIN_EMAIL', $parameters['payment_admin_email']);
        define('PAYMENT_ADMIN_PASSWORD', $parameters['payment_admin_password']);
        define('SYSTEMPAY_PROD_KEY', $parameters['systempay_prod_key']);
        define('SYSTEMPAY_TEST_KEY', $parameters['systempay_test_key']);
        define('PAYMENT_SMTP_USERNAME', $parameters['payment_smtp_username']);
        define('PAYMENT_SMTP_PASSWORD', $parameters['payment_smtp_password']);
        define('MAIL_SMTP_USERNAME', $parameters['mail_smtp_username']);
        define('MAIL_SMTP_PASSWORD', $parameters['mail_smtp_password']);
        define('AES_SECRET_KEY', $parameters['aes_secret_key']);
        define('AES_SECRET_IV', $parameters['aes_secret_iv']);
        define('SSO_PASSPHRASE', $parameters['sso_passphrase']);
        define('DEBUG_EMAIL', $parameters['debug_email']);
        if(isset($elements[1]))
        {
            define('ROUTE', $elements[0]."/".$elements[1]);
        }
        else
        {
            define('ROUTE', $elements[0]);         
        }

        define('ROUTE_FIRST_ELEMENT', $elements[0]);

        
        define('SIZE_RECHERCHE', $parameters['size_recherche']);
        define('SIZE_LIST', $parameters['size_list']);

        //set folders
        define('CONTROLLER', ROOT.'controller/');
        define('VIEW', ROOT.'view/');
        define('MODEL', ROOT.'model/');
        define('APPLICATION', ROOT.'application/');
        // set assets url
        define('ASSETS', HOST.'assets/');
        define('JS', ASSETS.'js/');
        define('CSS', ASSETS.'css/');
        define('IMG', ASSETS.'image/');

        define('HELPER', ROOT.'application/helper/');

        define('TWILIO_ID', $parameters['twilio_id']);
        define('TWILIO_TOKEN', $parameters['twilio_token']);

        $files_route = array();

        foreach($parameters_route as $key => $item){
              
              $files_route[$key]['css'] = explode(' ', $item['css']); 
              $files_route[$key]['js'] = explode(' ', $item['js']);               
        }

        define('FILES_ROUTE', $files_route);

        define('MAPS_API_KEY', $parameters['maps_api_key']);

        if(ROUTE == "auth/logout")
        {
            session_destroy();
            header('location: '.HOST.'auth/display');
            exit;
        }

        if(!isset($_SESSION['TOKEN']))
        {
            define('ROLE', 'NOTHING');
            define('TOKEN', '');
            // Defensive default: several controllers/views read PERSON_CONNECTED
            // unconditionally (e.g. Home::display()). Routes that reach this branch
            // are unauthenticated, so there's no real person data — an empty array
            // keeps that code from treating the bareword as an undefined constant
            // (silently coerced to the string 'PERSON_CONNECTED') if it's ever
            // reached without a session.
            define('PERSON_CONNECTED', []);
            if(
                ROUTE == "auth/display" 
                OR ROUTE == "auth/check" 
                OR ROUTE == "auth/lost-password" 
                OR ROUTE == "auth/create-password" 
                OR ROUTE == "auth/generate-new-password"
                OR ROUTE == "sendMailLostPassword" 
                OR ROUTE == "auth/lost-password-confirm" 
                OR ROUTE == "sendRequest"
                OR ROUTE == "sendSMSNewPassword" 
                OR ROUTE == "payment/success"
                OR ROUTE == "carte/jo2024"

            )
            {
            
            }
            elseif(ROUTE == "ea/connectApp")
            {
                if(isset($elements[2]))
                {

                    $controller = new Controller();
                    echo $controller->cryptoJsAesDecrypt(SSO_PASSPHRASE, $elements[2]);
                    
                    $requestAuth['username'] = $elements[3];
                    $requestAuth['password'] = $elements[2];
                    $params = $controller->cURL(API.'user/api/authenticate', 'AJAX_CALL', $requestAuth, 'POST');

                    $data['token'] = $params->token;


                    $personConnected = $controller->cURL(API.'person/display/'.$params->user->identifier, 'PHP_CALL', $data, 'GET');

                    // Same rule as Auth::checkAuth(): only trust the session data if the API
                    // actually confirmed this token/identifier pair, don't set it blindly.
                    if (!is_object($personConnected) || isset($personConnected->error) || !isset($personConnected->personId)) {
                        header('location: '.HOST.'auth/display');
                        exit;
                    }

                    session_regenerate_id(true);

                    $_SESSION['TOKEN'] = $params->token;
                    $_SESSION['IDENTIFIER'] = $params->user->identifier;
                    $_SESSION['ROLE'] = $params->user->roles;
                    $_SESSION['PERSON_CONNECTED'] = $personConnected;

                    header( "refresh:3;url=".HOST."ea/cartFromApp" );

                }
                else
                {

                }


            }
            else
            {
                header('location: '.HOST.'auth/display');
                exit;
            }
        }
        elseif(isset($_SESSION['TOKEN']))
        {
            if(ROUTE == "connexion")
            {
                header('location: home');
                exit;
            }
            elseif(ROUTE == "ea/connectApp")
            {
                header('location: '.HOST.'ea/cartFromApp');
                exit;
            }

            define('ROLE', $_SESSION['ROLE']);
            define('TOKEN', $_SESSION['TOKEN']);
            $ARRAY_PERSON_CONNECTED = json_decode(json_encode($_SESSION['PERSON_CONNECTED']), True);

            define('PERSON_CONNECTED', $ARRAY_PERSON_CONNECTED);
          
        }


    }
    /**
     * load class by autoload
     * @param $class
     */
    private static function autoload($class)
    {
        if(file_exists(MODEL.$class.'.php'))
        {
            include_once (MODEL.$class.'.php');
        }

        if (file_exists(APPLICATION.$class.'.php'))
        {
            include_once (APPLICATION.$class.'.php');
        }

        if (file_exists(CONTROLLER.$class.'.php'))
        {
            include_once(CONTROLLER.$class.'.php');
        }
    }
}
