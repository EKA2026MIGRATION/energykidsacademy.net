<?php

/**
 * Class Auth
 * Users Authentification 
 */

class Auth extends Controller
{

    public function displayAuth($request)
    {
        $this->render('render/auth/auth');
    }

    public function lostPassword($request)
    {
        $this->render('render/auth/lost-password');
    }

    public function createPassword($request)
    {
        $params['email'] = $request->email;
        $this->renderWithData('render/auth/create-password', $params);
    }

    public function lostPasswordConfirm($request)
    {
        $params['token'] = $request->token;

        $this->renderWithData('render/auth/lost-password-confirm', $params);
    }
    public function connectApp($request)
    {
        echo 'Connexion en cours..';
    }

    public function checkAuth($request)
    {
        unset($_SESSION['gymnases']);

    	$data = array();
    	$data['token'] = $request->token;
    	$personConnected = $this->cURL(API.'person/display/'.$request->userIdentifier, 'PHP_CALL', $data, 'GET');

        // The API call above is the only server-side proof that $request->token actually
        // authorizes access to $request->userIdentifier. If it failed (invalid/expired
        // token, or the token's owner isn't allowed to see this person), personConnected
        // won't be a real person object — refuse to establish a session with
        // client-supplied identity/role in that case.
        if (!is_object($personConnected) || isset($personConnected->error) || !isset($personConnected->personId)) {
            http_response_code(401);
            echo json_encode(['msg' => 'error', 'error' => 'Authentification invalide.']);
            return;
        }

        session_regenerate_id(true);

        $_SESSION['TOKEN'] = $request->token;
        $_SESSION['IDENTIFIER'] = $request->userIdentifier;
		$_SESSION['ROLE'] = $request->userRoles;
		$_SESSION['PERSON_CONNECTED'] = $personConnected;

        $datas = [
            'person' => $personConnected->personId,
            'action' => "EKA-CLIENT-CONNEXION",
        ];

        $response = $this->cURL(API.'historicPersonAction/create', 'AJAX_CALL', $datas, 'POST');

        $ARRAY_PERSON_CONNECTED = json_decode(json_encode($personConnected), True);

        $_SESSION['nbAddresses'] = count($ARRAY_PERSON_CONNECTED['addresses']);
        $_SESSION['nbPhones'] = count($ARRAY_PERSON_CONNECTED['phones']);
        $_SESSION['nbChildren'] = count($ARRAY_PERSON_CONNECTED['children']);

        if(  $_SESSION['nbAddresses'] > 0 && $_SESSION['nbAddresses'] > 0 &&  $_SESSION['nbChildren'] > 0 ) {
            $_SESSION['canRegister'] = 1;
        } else {
            $_SESSION['canRegister'] = 0; 
        }

		echo json_encode(['msg' => 'ok', 'TOKEN' => $request->token]);
    }

}
