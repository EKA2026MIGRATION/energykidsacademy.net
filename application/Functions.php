<?php

function use_helper($element)
{
  $elements = explode(',', $element);
  foreach($elements as $filename) {
    $filename = trim($filename);
    if(file_exists(HELPER.$filename.'.php')) {
      require_once(HELPER.$filename.'.php');
    }
  }
}


function encodeInt($id) {
  // Callers sometimes pass null/non-numeric values (e.g. missing session/API
  // data on an edge case) — a strict `int $id` hint turned that into a fatal
  // TypeError instead of just producing a harmless encoded value.
  $id = (int) $id;
  $id = $id*3 + 7000;
  return urlencode(base64_encode($id));
//  return base_convert($id, 16, 36);  // 16/36
}

function decodeInt($value) {
  $newResult = base64_decode(urldecode($value));
  //$newResult = base_convert($value, 36, 16);
  return ($newResult - 7000)/3;
}


function dd($var, $stop = 1) {
  echo '<pre>';
  print_r($var);
  echo '</pre>';
  if($stop == 1 ) exit;
}



function encrypt($string) {

  $encrypt_method = "AES-256-CBC";
  $secret_key     = AES_SECRET_KEY;
  $secret_iv      = AES_SECRET_IV;
  $key            = hash('sha256', $secret_key);    
  $iv             = substr(hash('sha256', $secret_iv), 0, 16);


  $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
  $output = base64_encode($output);
  return $output;
} 

function decrypt($string) {

  $encrypt_method = "AES-256-CBC";
  $secret_key     = AES_SECRET_KEY;
  $secret_iv      = AES_SECRET_IV;
  $key            = hash('sha256', $secret_key);    
  $iv             = substr(hash('sha256', $secret_iv), 0, 16);


  $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
  return $output;
} 