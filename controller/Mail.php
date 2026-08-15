<?php
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail extends Controller
{

    public function LostPassword($request)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'in-v3.mailjet.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_SMTP_USERNAME;
            $mail->Password   = MAIL_SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = '587';

            $mail->setFrom('contact@energykidsacademy.net', 'EnergyKidsAcademy');
            $mail->addAddress($request->email);


            $subject = 'EnergyKidsAcademy.net - Récupération de votre mot de passe';

            $prehead = 'EnergyKidsAcademy.net - Récupération de votre mot de passe';

            $content = 'Bonjour, vous venez de faire une demande pour récupérer votre mot de passe. Cliquez sur le lien ci-dessous pour créer un nouveau mot de passe.';

            $linkUrl = HOST . "auth/lost-password-confirm/token/" . $request->token . "/";

            $buttonText = 'Créer un nouveau mot de passe';

            $disclaimer = "Si vous n'êtes pas à l'origine de la demande de cet email, veuillez l'ignorer. <br>
            
            A bientôt, <br>
      
            EnergyKidsAcademy.net
            
            ";

            // Content
            $mail->isHTML(true);
            $mail->Subject = utf8_decode($subject);
            $mail->Body = utf8_decode($this->TemplateEmail($prehead, $content, $linkUrl, $buttonText, $disclaimer));
            $mail->IsHTML(true);
            $mail->send();
        } catch (Exception $e) {
            echo json_encode(["success" => false]);
        }
    }

    public function LostPasswordOld($request)
    {
        $url = HOST . "auth/lost-password-confirm/token/" . $request->token . "/";

        $contents =  'Bonjour, 
        <p>Vous venez de faire une demande pour récupérer votre mot de passe. </p>

        <p>Cliquez ou copier/coller le lien ci-dessous pour créer un nouveau mot de passe.</p>

        ' . $url . '

        <p>Si vous n\'avez pas effectuer cette demande, merci de ne pas tenir compte de cet e-mail.</p>';

        $to  = $request->email;
        $subject = "Récupération de votre mot de passe";

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= "From: Energy Kids Academy <contact@energyacademy.fr" . "\r\n";

        mail($to, $subject, $contents, $headers);
        echo json_encode(["success" => true]);
    }


    public function TemplateEmail($prehead, $content, $linkUrl, $buttonText, $disclaimer, $disclaimerLeft = false)
    {
        ob_start();
?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">

        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
		   BUTTONS
		   ------------------------------------- */
                .btn {
                    border-radius: 6px;
                    background-color: #182d61;
                    text-align: center;
                    border: none;
                    cursor: pointer;
                }

                .btn:hover {
                    transition: 0.4s;
                    background-color: #182d61;
                }

                .btn-text {
                    color: #ffffff !important;
                    font-family: Arial !important;
                    font-weight: bold !important;
                    font-size: 14px !important;
                }

                .btn-2,
                a.btn-2 {
                    height: 42px;
                    width: 217px;
                    border-radius: 6px;
                    background-color: #182d61;
                    display: block;
                    margin: 24px auto;
                    color: #ffffff !important;
                    font-family: Arial !important;
                    font-size: 14px !important;
                    font-weight: bold !important;
                    cursor: pointer;
                    line-height: 42px !important;
                    padding: 0 24px;
                    text-align: center !important;
                    border: none;
                    cursor: pointer;
                }

                .btn-2:hover,
                a.btn-2:hover {
                    transition: 0.4s;
                    background-color: #182d61;
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


                                                                                        <!--[if gte mso 9]>
																				 <table border="0" cellpadding="0" cellspacing="0" width="100%">
																					 <tr style="padding-top:40px;">
																						 <td align="center" valign="top">
																							 <table border="0" cellpadding="30" cellspacing="0" width="598" class="flexibleContainer">
																								 <tr>
																									 <td style="padding-top:0;" align="center" valign="top" width="598">
																										 <table border="0" cellpadding="0" cellspacing="0" width="60%" class="btn">
																											 <tr>
																												 <td align="center" valign="middle" style="padding: 12px 24px;">
																													 <a class="btn-text" href="<?= $linkUrl; ?>" target="_blank"><?= $buttonText; ?></a>
																												 </td>
																											 </tr>
																										 </table>
											 
																									 </td>
																								 </tr>
																							 </table>
																						 </td>
																					 </tr>
																				 </table>
																				 <![endif]-->

                                                                                        <!--[if !mso]>-->
                                                                                        <a href="<?= $linkUrl; ?>" class="btn-2"><?= $buttonText; ?></a>
                                                                                        <!--<![endif]-->


                                                                                        <div class="<?php if (!$disclaimerLeft) : ?>center-text<?php endif; ?>">
                                                                                            <?= $disclaimer; ?>
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
