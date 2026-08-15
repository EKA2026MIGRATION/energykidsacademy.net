<?php

/**
 * Class Controller
 *
 * to organize all controller and create the view
 */
class Controller
{

    private $view;

    public function __construct()
    {
        $this->view = new View();
        if(isset($_SESSION['IDENTIFIER']))
        {
            $personConnected = $this->cURL(API.'person/display/'.$_SESSION['IDENTIFIER'], 'PHP_CALL', '', 'GET');
            $_SESSION['PERSON_CONNECTED'] = $personConnected;
        }

        if( isset($_SESSION['canRegister']) && $_SESSION['canRegister'] == 0  && isset($_SESSION['PERSON_CONNECTED'])  ) {

            $addresses = isset($_SESSION['PERSON_CONNECTED']->addresses) && is_array($_SESSION['PERSON_CONNECTED']->addresses) ? $_SESSION['PERSON_CONNECTED']->addresses : [];
            $phones = isset($_SESSION['PERSON_CONNECTED']->phones) && is_array($_SESSION['PERSON_CONNECTED']->phones) ? $_SESSION['PERSON_CONNECTED']->phones : [];
            $children = isset($_SESSION['PERSON_CONNECTED']->children) && is_array($_SESSION['PERSON_CONNECTED']->children) ? $_SESSION['PERSON_CONNECTED']->children : [];

            $_SESSION['nbAddresses'] = count($addresses);
            $_SESSION['nbPhones'] = count($phones);
            $_SESSION['nbChildren'] = count($children);

            if(  $_SESSION['nbAddresses'] > 0 && $_SESSION['nbPhones'] > 0 &&  $_SESSION['nbChildren'] > 0 ) {
                $_SESSION['canRegister'] = 1;
            } else {
                $_SESSION['canRegister'] = 0;
            }
        }

        if(!isset($_SESSION['gymnases']) || count($_SESSION['gymnases']) < 1  ) {
            $products =  $this->cURL(API.'product/list/frontvisibility/11', 'PHP_CALL', '', 'GET');
            $_SESSION['gymnases'] = [];
            if($products) {
                foreach($products as $product) {
                    if($product->frontMenu != "" && $product->frontMenu != null) $_SESSION['gymnases'][] = $product;
                }
            }
        }
    }

    public function render($template)
    {
        $myView = $this->view;
        $myView->setTemplate($template);
        $myView->render('');
    }

    public function renderWithData($template, $data)
    {
        $myView = $this->view;
        $myView->setTemplate($template);
        $data = (object) $data;
        $myView->render($data);
    }


    public function renderHtml($template, $data)
    {
        $myView = $this->view;
        $myView->setTemplate($template);
        $data = (object) $data;
        $myView->renderWithoutTemplate($data);
    }

    public function cryptoJsAesDecrypt($passphrase, $jsonString){
        $jsondata = json_decode($jsonString, true);
        $salt = hex2bin($jsondata["s"]);
        $ct = base64_decode($jsondata["ct"]);
        $iv  = hex2bin($jsondata["iv"]);
        $concatedPassphrase = $passphrase.$salt;
        $md5 = array();
        $md5[0] = md5($concatedPassphrase, true);
        $result = $md5[0];
        for ($i = 1; $i < 3; $i++) {
            $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
            $result .= $md5[$i];
        }
        $key = substr($result, 0, 32);
        $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
        return json_decode($concatedPassphrase, true);
    }

    public function cURL($url, $type, $data, $method)
    {

        $headers = array();
        if(isset($data['token']))
        {
            $headers[] = "Authorization: Bearer ".$data['token'];
        }
        elseif(TOKEN != '')
        {
            $headers[] = "Authorization: Bearer ".TOKEN;
        }


        if($data != '')
        {
        $data_string = json_encode($data);
        $headers[] = "Content-Type: application/json";
        $headers[] = "Content-Length: ".strlen($data_string);
        }


        $state_ch = curl_init();
        curl_setopt($state_ch, CURLOPT_URL, $url);
        curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($state_ch, CURLOPT_CUSTOMREQUEST, $method);

        if($data != '')
        {
            curl_setopt($state_ch, CURLOPT_POSTFIELDS, $data_string);
        }

        curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
        $state_result = curl_exec ($state_ch);
        $data = json_decode($state_result);
        if(isset($data->error) && $type == "PHP_CALL")
        {

        }
        elseif(isset($data->error) && $type == "AJAX_CALL")
        {

        }
        //print_r($data);
        return $data;

    }
    public function cURLWithToken($url, $type, $data, $method, $token)
    {

        $headers = array();
        $headers[] = "Authorization: Bearer ".$token;



        if($data != '')
        {
        $data_string = json_encode($data);
        $headers[] = "Content-Type: application/json";
        $headers[] = "Content-Length: ".strlen($data_string);
        }


        $state_ch = curl_init();
        curl_setopt($state_ch, CURLOPT_URL, $url);
        curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($state_ch, CURLOPT_CUSTOMREQUEST, $method);

        if($data != '')
        {
            curl_setopt($state_ch, CURLOPT_POSTFIELDS, $data_string);
        }

        curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
        $state_result = curl_exec ($state_ch);
        $data = json_decode($state_result);
        if(isset($data->error) && $type == "PHP_CALL")
        {

        }
        elseif(isset($data->error) && $type == "AJAX_CALL")
        {

        }
        //print_r($data);
        return $data;

    }

    public function redirect($route)
    {
      $this->view->redirect($route);
    }

    public function renderJson($data) {
        echo json_encode($data);
    }

}
