<?php
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use_helper('translation');
use_helper('dates');


class Payment extends Controller
{
    public function Authenticate()
    {
        $requestAuth['username'] = PAYMENT_ADMIN_EMAIL;
        $requestAuth['password'] = PAYMENT_ADMIN_PASSWORD;
        $params = $this->cURL(API.'user/api/authenticate', 'AJAX_CALL', $requestAuth, 'POST');
        return $params->token;

    }

    public function createSignatures($post, $key) {
        $contenu_signature = "";
        $params = $post;
        ksort($params);

        foreach ($params as $nom => $valeur) {
          if(substr($nom,0,5) == "vads_") {
            // C'est un champ utilisé pour calculer la signature
            $contenu_signature .= $valeur.'+';
          }
        };

        $contenu_signature .= $key;
        $signature_calculee = sha1($contenu_signature);


        return array('contenu' => $contenu_signature, 'calculee' => $signature_calculee );

    }



    /**
     * Callback SystemPay après paiement
     * - Valide la signature
     * - Met à jour la facture
     * - Met à jour les inscriptions
     * - Envoie les emails de confirmation
     */
    public function Success($request)
    {
        // ============================================================
        // #1 AUTHENTIFICATION API
        // ============================================================
        try {
            $jwtToken = $this->Authenticate();
        } catch(Exception $e) {
            echo 'AUTH_ERROR: ' . $e->getMessage();
            return;
        }

        if(strlen($jwtToken) <= 3) {
            echo 'token error';
            return;
        }

        // ============================================================
        // #2 CONFIGURATION MODE (PRODUCTION ou TEST)
        // ============================================================
        $mode = 'PRODUCTION'; // Changer en 'TEST' pour les tests
        $debug = false; // Les emails de debug (panier/facture complets) ne doivent jamais partir en PRODUCTION
        $testEmail = DEBUG_EMAIL;


        if($mode == 'PRODUCTION' && $_POST['vads_ctx_mode'] == "PRODUCTION") {
            $key = SYSTEMPAY_PROD_KEY;
            $conf = '';
        } else {
            $key = SYSTEMPAY_TEST_KEY;
            $conf = '_TEST';
        }

        // ============================================================
        // #3 VALIDATION SIGNATURE BANQUE
        // ============================================================
        $signatures = $this->createSignatures($_POST, $key);
        $signature_calculee = $signatures['calculee'];

        // En mode TEST, on bypass la validation de signature
        if($mode == 'TEST') {
            $signatureValide = true;
        } else {
            $signatureValide = isset($_POST["signature"])
                && $signature_calculee == $_POST["signature"]
                && $_POST['vads_result'] == 00;
        }

        if(!$signatureValide) {
            // ECHEC PAIEMENT - Notifier et sortir
            $html = "";
            foreach($_POST as $key => $data) {
                $html .= $key.' : '.$data.' <br/>';
            }

            $failMail = ($mode == 'PRODUCTION') ? "contact@energykidsacademy.net" : $testEmail;

            try {
                $this->sendViaPhpMailer($failMail, "EKA - ECHEC PAIEMENT", $html);
            } catch(Exception $e) {
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                $headers .= "From: Energy Kids Academy <contact@energykidsacademy.net>" . "\r\n";
                mail($failMail, "EKA - ECHEC PAIEMENT", $html, $headers);
            }

            http_response_code(200);
            echo 'OK';
            return;
        }

        // ============================================================
        // #4 RECUPERATION DONNEES PAIEMENT
        // ============================================================
        $internalOrder = $_POST['vads_order_info'];
        $amount = $_POST['vads_amount'] / 100;
        $email = $_POST['vads_cust_email'];
        $invoiceId = $_POST['vads_order_id'];

        // ============================================================
        // #4b PROTECTION DOUBLE TRAITEMENT
        // ============================================================
        try {
            $existingInvoice = $this->cURLWithToken(API.'invoice/'.$invoiceId, 'PHP_CALL', '', 'GET', $jwtToken);
            if (isset($existingInvoice->status) &&
                ($existingInvoice->status === 'payed' || $existingInvoice->status === 'payed_TEST')) {
                // Facture déjà traitée, on répond OK mais on ne refait pas le traitement
                http_response_code(200);
                echo 'OK';
                return;
            }
        } catch(Exception $e) {
            // Continuer si on ne peut pas vérifier
        }

        // ============================================================
        // #5 RECUPERATION DU PANIER
        // ============================================================
        try {
            $cartKey = isset($_POST['vads_order_info2']) ? $_POST['vads_order_info2'] : $invoiceId;
            $cartResponse = $this->cURLWithToken(API.'registration/list/'.$cartKey.'/cart', 'PHP_CALL', '', 'GET', $jwtToken);

            // Convertir en array si c'est un objet
            if (is_object($cartResponse)) {
                $params['cart'] = json_decode(json_encode($cartResponse), false); // garde comme objets mais force le format
            } else {
                $params['cart'] = $cartResponse;
            }

            if (empty($params['cart']) || (is_array($params['cart']) && count($params['cart']) == 0)) {
                $cartResponse2 = $this->cURLWithToken(API.'registration/list/'.$invoiceId.'/cart', 'PHP_CALL', '', 'GET', $jwtToken);
                if (is_object($cartResponse2)) {
                    $params['cart'] = json_decode(json_encode($cartResponse2), false);
                } else {
                    $params['cart'] = $cartResponse2;
                }
            }
        } catch(Exception $e) {
            $params['cart'] = [];
        }

        // DEBUG: Envoi email pour vérifier le panier
        if($mode == 'TEST' || $debug) {
            $debugCart = '<h3>DEBUG - Récupération panier #5</h3>';
            $debugCart .= '<b>vads_order_info2:</b> ' . (isset($_POST['vads_order_info2']) ? $_POST['vads_order_info2'] : 'NON DEFINI') . '<br/>';
            $debugCart .= '<b>vads_order_id (invoiceId):</b> ' . $invoiceId . '<br/>';
            $debugCart .= '<b>Cart Key utilisée:</b> ' . $cartKey . '<br/>';
            $debugCart .= '<b>Type retour API:</b> ' . gettype($params['cart']) . '<br/>';
            $debugCart .= '<b>Nombre items panier:</b> ' . (is_array($params['cart']) ? count($params['cart']) : (is_object($params['cart']) ? 'OBJET' : 'AUTRE')) . '<br/>';
            $debugCart .= '<b>URL API appelée:</b> ' . API.'registration/list/'.$cartKey.'/cart' . '<br/>';
            $debugCart .= '<hr/><b>Contenu panier (brut):</b><pre>' . print_r($params['cart'], true) . '</pre>';
            $debugCart .= '<hr/><b>Contenu panier (JSON):</b><pre>' . json_encode($params['cart'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
            $this->sendViaPhpMailer($testEmail, "DEBUG - Panier #5", $debugCart);
        }

        // ============================================================
        // #6 MISE A JOUR FACTURE
        // ============================================================
        $arrayInvoice = array();
        $arrayInvoice['status'] = "payed".$conf;
        $arrayInvoice['child'] = "32";
        $arrayInvoice['nameFr'] = "M/Mme ".$_POST['vads_cust_name'];
        $arrayInvoice['nameEn'] = "Mr/Mrs ".$_POST['vads_cust_name'];
        $arrayInvoice['descriptionFr'] = "";
        $arrayInvoice['descriptionEn'] = "";
        $arrayInvoice['date'] = date('Y-m-d H:i:s');
        $arrayInvoice['paymentMethod'] = "CB";
        $arrayInvoice['priceTtc'] = $amount;
        $arrayInvoice['prices'] = $amount;
        $arrayInvoice['address'] = $_POST['vads_cust_address'];
        $arrayInvoice['postal'] = $_POST['vads_cust_zip'];
        $arrayInvoice['town'] = $_POST['vads_cust_city'];


        // DEBUG: Envoi email de vérification en mode TEST
        if($mode == 'TEST' || $debug) {
            $debugHtml = '<h3>DEBUG - Mise à jour facture #6</h3>';
            $debugHtml .= '<b>Invoice ID:</b> ' . $invoiceId . '<br/>';
            $debugHtml .= '<b>Nom:</b> ' . $arrayInvoice['nameFr'] . '<br/>';
            $debugHtml .= '<b>Montant:</b> ' . $amount . ' euros<br/>';
            $debugHtml .= '<b>Numéro interne:</b> ' . $internalOrder . '<br/>';
            $debugHtml .= '<b>Status:</b> ' . $arrayInvoice['status'] . '<br/>';
            $debugHtml .= '<b>API URL:</b> ' . API.'invoice/modify/'.$invoiceId . '<br/>';
            $debugHtml .= '<hr/><b>Données envoyées:</b><pre>' . json_encode($arrayInvoice, JSON_PRETTY_PRINT) . '</pre>';
            $this->sendViaPhpMailer($testEmail, "DEBUG - Mise à jour facture", $debugHtml);
        }

        try {
            $this->cURLWithToken(API.'invoice/modify/'.$invoiceId, 'AJAX_CALL', $arrayInvoice, 'PUT', $jwtToken);
        } catch(Exception $e) {
            echo 'INVOICE_ERROR: ' . $e->getMessage();
            exit();
        }

        // ============================================================
        // #7 MISE A JOUR INSCRIPTIONS + GENERATION HTML RECAPITULATIF
        // ============================================================
        $htmlProduct = '<h2>Récapitulatifs inscriptions Energy Kids Academy</h2><br/><br/><br/>';
        $htmlProduct .= 'Vous trouverez ci-dessous un récapitulatif pour chaque enfant et chaque inscription :';
        $customerMails = [];

        if (!empty($params['cart'])) {
            foreach ($params['cart'] as $cart) {

                // Mise à jour status inscription
                $requestSend['status'] = "payed".$conf;
                try {
                    $this->cURLWithToken(API."registration/modify/".$cart->registrationId, 'AJAX_CALL', $requestSend, 'PUT', $jwtToken);
                } catch(Exception $e) {
                    // Continue avec les autres inscriptions
                }

                // Génération HTML récapitulatif
                $htmlProduct .= '<div style="height:10px;"></div><span style="font-weight: bold"> '.$cart->product->nameFr.'</span>';
                $htmlProduct .= '<b>'.$cart->child->firstname.' '.$cart->child->lastname.'</b><br/>';
                $htmlProduct .= 'Date(s) : <br/>';

                foreach ($cart->sessions as $date) {
                    $htmlProduct .= date('d/m/Y', strtotime($date->date));
                    if(showHour($date->start)) {
                        $htmlProduct .= ' de '.$date->start;
                    }
                    if(showHour($date->end)) {
                        $htmlProduct .= ' à '.$date->end;
                    }
                    $htmlProduct .= '<br/>';
                }

                // Collecte des emails custom par produit
                if(isset($cart->product->mail)) {
                    $customerMails[$cart->product->mail->mailId] = $cart->product->mail;
                }
            }
        }

        $htmlProduct .= '<br/>Notre secrétariat est à votre disposition 7/7j au 01.47.01.59.60 et à contact@energykidsacademy.net<br/>
            Sportivement,<br/>
            Energy Kids Academy';

        // ============================================================
        // #8 GENERATION EMAIL CONFIRMATION
        // ============================================================
        $newLink = "https://appli-v.net/download/i/v/".encodeInt($invoiceId).'/i/c/';

        $html = '<h2>Inscription Energy Kids Academy</h2><br/><br/><br/>';
        $html .= 'Votre règlement a été validé par la Banque Populaire Val de France.<br/><br/>';
        $html .= "Votre paiement par carte bancaire d'un montant de ".$amount." euros a été enregistré sous la référence n°".$internalOrder;
        $html .= '<br/> Vous pouvez également télécharger une facture en cliquant sur ce lien:<br/>';
        $html .= '<a href="'.$newLink.'">Facture inscription Energy Kids Academy</a><br/>';
        $html .= '<br/>Notre secrétariat est à votre disposition 7/7j au 01.47.01.59.60 et à contact@energykidsacademy.net<br/>
            Sportivement,<br/>
            Energy Kids Academy';

        // ============================================================
        // #9 ENVOI EMAILS CONFIRMATION
        // ============================================================
        $subjetConfirm = "Confirmation - Inscription Energy Kids Academy";
        $subjectProduct = "Récapitulatifs produits - Inscription Energy Kids Academy";

        if($mode == 'PRODUCTION') {
            $personsMails = [$email, "contact@energykidsacademy.net"];
        } else {
            $personsMails = [$testEmail];
        }

        foreach($personsMails as $personMail) {
            try {
                $contentEmail = $this->TemplateEmail($subjetConfirm, $html);
                $this->sendViaPhpMailer($personMail, $subjetConfirm, $contentEmail);

                $contentEmail = $this->TemplateEmail($subjectProduct, $htmlProduct);
                $this->sendViaPhpMailer($personMail, $subjectProduct, $contentEmail);
            } catch(Exception $e) {
                // Continue avec les autres emails
            }
        }

        // ============================================================
        // #10 ENVOI EMAILS CUSTOM (par produit)
        // ============================================================
        if(!empty($customerMails)) {
            foreach($customerMails as $mail) {
                try {
                    $contentEmail = $this->TemplateEmail($mail->subjectFr, $mail->contentFr);
                    $destMail = ($mode == 'PRODUCTION') ? $email : $testEmail;
                    $this->sendViaPhpMailer($destMail, $mail->subjectFr, $contentEmail);
                } catch(Exception $e) {
                    // Continue
                }
            }
        }


        // ============================================================
        // #11 REPONSE A LA BANQUE
        // ============================================================
        http_response_code(200);
        echo 'OK';
    }

    public function Confirmation($request)
    {
        return $this->redirect('utilisateur/historique');  
    }

    public function sendViaPhpMailer($destEmail, $subject, $content) {
      try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'in-v3.mailjet.com';
            $mail->SMTPAuth   = true;
                 $mail->Username   = PAYMENT_SMTP_USERNAME;
                $mail->Password   = PAYMENT_SMTP_PASSWORD;

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = '587';

            $mail->setFrom('contact@energykidsacademy.net', 'EnergyKidsAcademy');
            $mail->addAddress($destEmail);


            $subject = 'EnergyKidsAcademy.net - '.$subject;

            $prehead = 'EnergyKidsAcademy.net - '.$subject;

            // Content
            $mail->isHTML(true);
            $mail->Subject = utf8_decode($subject);
            $mail->Body = utf8_decode($content);
            $mail->IsHTML(true);
            $mail->send();
        } catch (Exception $e) {
            throw $e; // Re-throw to be caught by calling function
        }
    }


    public function TemplateEmail($prehead, $content)
    {
        ob_start();
?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" lang="fr">

        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <meta name="format-detection" content="telephone=no" />
            <title>Template Hippocampe</title>
            <style type="text/css">
                /* -------------------------------------
		   RESET STYLE
		   ------------------------------------- */
                body,
                #bodyTable,
                #bodyCell,
                #bodyCell {
                    height: 100% !important;
                    margin: 0;
                    padding: 0;
                    width: 100% !important;
                    font-family: sans-serif;
                }

                table {
                    border-collapse: collapse;
                }

                table[id=bodyTable] {
                    width: 100% !important;
                    margin: auto;
                    max-width: 600px !important;
                    color: #4A5056;
                    font-weight: normal;
                }

                /* -------------------------------------
		   COMPATIBILITY
		   ------------------------------------- */
                table,
                td {
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                }

                img {
                    -ms-interpolation-mode: bicubic;
                    outline: none;
                    text-decoration: none;
                }

                body,
                table,
                td,
                p,
                a,
                li,
                blockquote {
                    -ms-text-size-adjust: 100%;
                    -webkit-text-size-adjust: 100%;
                    font-weight: normal !important;
                }

                /* -------------------------------------
		   STRUCTURE
		   ------------------------------------- */
                body,
                #bodyTable {
                    background-color: #fff;
                }

                #emailHeader {
                    background-color: #fff;
                }

                #emailTitle {
                    background: #222529;
                    border-radius: 6px 6px 0px 0px;
                }

                #emailBody {
                    background-color: #F7FAFC;
                    border-radius: 0px 0px 6px 6px;
                    border: 1px solid #E2E8F0;
                }

                #emailFooter {
                    background-color: #fff;
                }

                /* -------------------------------------
		   LOGO
		   ------------------------------------- */
                .logo {
                    width: 100%;
                    text-align: center;
                    margin-top: 24px;
                    margin-bottom: 24px;
                }

                /* -------------------------------------
		   TYPOGRAPHY
		   ------------------------------------- */
                .top-text,
                #emailFooter p {
                    font-family: Arial;
                    font-style: normal;
                    font-size: 11px;
                    line-height: 16px;
                    text-align: center;
                    color: #4A5056;
                }

                .email-title-text {
                    text-align: left;
                    width: 100%;
                    font-family: Arial;
                    font-style: normal;
                    font-weight: bold;
                    font-size: 19px;
                    line-height: 22px;
                    color: #fff;
                }

                #emailBody p {
                    font-family: Arial;
                    font-style: normal;
                    font-weight: normal;
                    font-size: 14px;
                    line-height: 24px;
                    color: #4A5568;
                    margin-bottom: 15px;
                }

                #emailBody .code {
                    font-family: Arial;
                    font-style: normal;
                    font-weight: bold;
                    font-size: 26px;
                    line-height: 24px;
                    color: #0D52A1;
                    margin-top: -8px;
                    display: block;
                }

                #emailBody .help-text {
                    font-family: Arial;
                    font-style: normal;
                    font-weight: normal;
                    font-size: 11px;
                    line-height: 16px;
                    display: block;
                    text-align: center;
                    color: #4A5056;
                }

                #emailBody .center-text {
                    text-align: center;
                    width: 100%;
                    font-family: Arial;
                    font-style: normal;
                    font-weight: normal;
                    font-size: 12px;
                    line-height: 20px;
                    text-align: center;
                    color: #6E757C;
                }

                #emailBody ul {
                    list-style: none;
                    padding-left: 0;
                }

                #emailBody ul li {
                    margin-bottom: 16px;
                    position: relative;
                    padding-left: 24px;
                    font-size: 15px;
                    line-height: 24px;
                    color: #4a5568;
                    font-weight: normal;
                }

                #emailBody ul li:before {
                    content: "";
                    position: absolute;
                    width: 4px;
                    height: 4px;
                    top: 10px;
                    left: 4px;
                    border-radius: 4px;
                    background: #0054A8;
                    z-index: 1;
                }

                #emailBody ul li:after {
                    content: "";
                    position: absolute;
                    top: 5.5px;
                    left: 0;
                    width: 12px;
                    height: 12px;
                    border-radius: 12px;
                    background-color: #d8ecd0;
                }

                #emailBody a {
                    font-family: Arial;
                    font-style: normal;
                    font-weight: 500;
                    font-size: 14px;
                    line-height: 16px;
                    color: #0091EA;
                    text-decoration: none;
                }

                #emailBody a:hover {
                    transition: 0.4s;
                    color: #0091EA;
                }

                #emailBody .help-link {
                    font-family: Arial;
                    font-style: normal;
                    font-weight: 500;
                    font-size: 14px;
                    line-height: 16px;
                    color: #0091EA;
                    text-decoration: none;
                    font-size: 11px;
                    line-height: 16px;
                    display: block;
                    text-align: center;
                }

                #emailBody .help-link:hover {
                    transition: 0.4s;
                    color: #0D52A1;
                }

                #emailHeader a,
                #emailFooter a {
                    font-family: Arial;
                    font-style: normal;
                    font-weight: 500;
                    font-size: 11px;
                    color: #0091EA;
                    text-decoration: none;
                }

                #emailHeader a:hover,
                #emailFooter a:hover {
                    transition: 0.4s;
                    color: #0D52A1;
                }



                /* -------------------------------------
		   MOBILE
		   ------------------------------------- */
                @media only screen and (max-width: 480px) {

                    body {
                        width: 100% !important;
                        min-width: 100% !important;
                    }

                    table[id="emailHeader"],
                    table[id="emailBody"],
                    table[id="emailFooter"],
                    table[id="emailTitle"],
                    table[class="flexibleContainer"] {
                        width: 100% !important;
                    }

                    td[class="flexibleContainerBox"],
                    td[class="flexibleContainerBox"] table {
                        display: block;
                        width: 100%;
                        text-align: left;
                    }
                }
            </style>
            <!--[if mso 12]>
		 <style type="text/css">
			 .flexibleContainer{display:block !important; width:100% !important;}
		 </style>
	 <![endif]-->

            <!--[if mso 14]>
		 <style type="text/css">
			 .flexibleContainer{display:block !important; width:100% !important;}
		 </style>
	 <![endif]-->
            <!--[if gte mso 9]>
		 <style>
			 ul {
				 padding-left: 0 !important;
			 }
		 </style>
	  <![endif]-->

        </head>

        <body bgcolor="#fff" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
            <center style="background-color: #fff;">
                <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed; max-width:100% !important; width: 100% !important; min-width: 100% !important;">
                    <tr>
                        <td align="center" valign="top" id="bodyCell">


                            <!-- ***************************  HEADER = TOP-TEXT + LOGO *************************** -->
                            <table bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="600" id="emailHeader">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td align="center" valign="top">
                                                    <table border="0" cellpadding="10" cellspacing="0" width="600" class="flexibleContainer">
                                                        <tr>
                                                            <td valign="top" width="600">
                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                    <tr>
                                                                        <td valign="middle" class="flexibleContainerBox">
                                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:100%; margin-top: 24px;">
                                                                                <tr>
                                                                                    <td align="left">
                                                                                        <div class="logo">
                                                                                            <img style="max-width: 170px;" src="https://www.energykidsacademy.fr/assets/img/energy-kids-academy.svg" />
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- *************************** END HEADER = TOP-TEXT + LOGO *************************** -->





                            <!-- *************************** EMAIL TITLE *************************** -->
                            <table bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" width="600" id="emailTitle">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td align="center" valign="top">
                                                    <table border="0" cellpadding="32" cellspacing="0" width="600" class="flexibleContainer">
                                                        <tr>
                                                            <td valign="top" width="600">
                                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                    <tr>
                                                                        <td valign="middle" class="flexibleContainerBox">
                                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:100%;">
                                                                                <tr>
                                                                                    <td align="left">
                                                                                        <div class="email-title-text">
                                                                                            <?= $prehead; ?>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- ***************************  END EMAIL TITLE *************************** -->





                            <!-- *************************** EMAIL CONTENT *************************** -->
                            <table bgcolor="#F7FAFC" border="0" cellpadding="0" cellspacing="0" width="598" id="emailBody">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td align="center" valign="top">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="598" class="flexibleContainer">
                                                        <tr>
                                                            <td align="center" valign="top" width="598">
                                                                <table border="0" cellpadding="32" cellspacing="0" width="100%">
                                                                    <tr>
                                                                        <td align="center" valign="top">
                                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                                <tr>
                                                                                    <td valign="top">
                                                                                        <p>
                                                                                            <?= $content; ?>
                                                                                        </p>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- *************************** END EMAIL CONTENT *************************** -->
                        </td>
                    </tr>
                </table>
            </center>
        </body>

        </html>
    <?php
        $mailContent = ob_get_clean();
        return $mailContent;
    }
}