<?php

/**
 * Class Photo
 *
 */

class Photo extends Controller
{


    public function savePhoto($request)
    {
      $allowedFolders = ['person', 'child'];
      $folder = $request->folder;

      if (!in_array($folder, $allowedFolders, true)) {
          http_response_code(400);
          echo json_encode(["error" => "Dossier non autorisé."]);
          return;
      }

      $base64 = $request->base64;
      $decoded = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64), true);

      if ($decoded === false || @getimagesizefromstring($decoded) === false) {
          http_response_code(400);
          echo json_encode(["error" => "Fichier image invalide."]);
          return;
      }

      $key = sha1(uniqid(mt_rand(),true));

      file_put_contents('../EnergyAcademyFront/uploads/'.$folder.'/'.$key.'.jpg', $decoded);

      echo json_encode(["url" => 'uploads/'.$folder.'/'.$key.'.jpg']);

    }


}

