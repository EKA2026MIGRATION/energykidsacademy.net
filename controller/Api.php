<?php

/**
 * Class API
 *
 */

require('php-library/twilio-php-master/Twilio/autoload.php');
use Twilio\TwiML\VoiceResponse;
use Twilio\Rest\Client;
use Twilio\TwiML\MessagingResponse;
$dateTimeZoneParis = new DateTimeZone("Europe/Paris");

class Api extends Controller
{

    public function sendRequest($request)
    {
    
   		if($request->type == "GET" || $request->type == "DELETE") // Pas d'envoie de données car GET ou DELETE
   		{
        // Always proxy to our own API — never let the client point this at an
        // arbitrary external URL (that was an open SSRF: $request->ressource used
        // to bypass the API. prefix entirely).
        $url = API.$request->url;
        $data = $this->cURL($url, 'AJAX_CALL', '', $request->type);
   		}
   		elseif($request->type == "POST" || $request->type == "PUT") // Si c'est un POST ou PUT il y a un envoi de données
   		{

        if(isset($request->data))
        {
          $requestSend = $request->data;
          
          if(isset($request->links)) // Si il y a un array in array LINKS
          {
             $requestSend['links'] = $request->links;
          }
        
          if(isset($request->preferences)) // Si il y a un array in array LINKS
          {
             $requestSend['preferences'] = $request->preferences;
          }
          if(isset($request->relations)) // Si il y a un array in array LINKS
          {
             $requestSend['relations'] = $request->relations;
          }

        }
        else
        {
          $requestSend = "";
        }

   			$data = $this->cURL(API.$request->url, 'AJAX_CALL', $requestSend, $request->type);

   		}
        
        echo json_encode($data);
    }

    public function generateRandomString($length = 8) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }

    public function sendSMSNewPassword($request)
    {
  
      $headers = array();
      $headers[] = "Content-Type: application/json";
      $plainPassword = $this->generateRandomString();
    
      $state_ch = curl_init();
      curl_setopt($state_ch, CURLOPT_URL, 'https://api.appli-v.net/user/api/reset-password-confirm/'.$request->token);
      curl_setopt($state_ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($state_ch, CURLOPT_CUSTOMREQUEST, 'PUT');
      $dataToSend = array('plainPassword' => $plainPassword); 
  
      curl_setopt($state_ch, CURLOPT_POSTFIELDS, json_encode($dataToSend));
      $headers[] = "Content-Length: ".strlen(json_encode($dataToSend));

      curl_setopt($state_ch, CURLOPT_HTTPHEADER, $headers);
      $state_result = curl_exec ($state_ch);
      $data = json_decode($state_result);

      if($data->allowUse) {
        $client = new Client(TWILIO_ID, TWILIO_TOKEN);
  
        // Use the client to do fun stuff like send text messages!
        $client->messages->create(
            // the number you'd like to send the message to
            $request->phone,
            array(
                // A Twilio phone number you purchased at twilio.com/console
                'from' => '+33644641085',
                // the body of the text message you'd like to send
                'body' => "Bonjour,\n\nVous venez de demander un nouveau mot de passe sur Energy Kids Academy\n\nVoici le nouveau mot de passe : ".$plainPassword."\n\nVous pouvez désormais vous connecter et le modifier si vous le souhaitez.\n\nBien cordialement,\n\nEnergy Kids Academy"
            )
        );
        echo json_encode(["message" => 'SMS envoyé']);
      }

    
    }
}