<?php
use_helper('dates');
/**
 *
 * List of functions HTML / FORMAT
 * used only in View templates
 *
 **/


$GLOBALS['files_accepted'] = ['jpg', 'jpeg', 'png'];
$tvPath    = 'uploads/tv/random';
$files = is_dir($tvPath) ? scandir($tvPath) : [];

foreach($files as $file) {
    $element = explode('.', $file);
    if(isset($element[1]) && (in_array($element[1], $GLOBALS['files_accepted']))) {
        $GLOBALS['random_images'][] = $file;
    }
}


function showPhoto($type, $string) {

  if ($string == '') {
      if ($type == "profil") {
          $photo = IMG."no_photo.jpg";
      } elseif ($type == "other") {
          $photo = IMG."no_photo_2.jpg";
      } elseif( $type == "random") {
        $nb = rand(0, count($GLOBALS['random_images']) - 1);
        $photo = HOST.'uploads/tv/random/'.$GLOBALS['random_images'][$nb];
      } else {
          $photo = HOST.$string;
      }
  } else {
    if(!file_exists(ROOT.$string)) {
      $nb = rand(0, count($GLOBALS['random_images']) - 1);
      $photo = HOST.'uploads/tv/random/'.$GLOBALS['random_images'][$nb];
    } else {
      $photo = HOST.$string;
    }
  }
  return $photo;
}


function showIcon($type_name)
{
    if(!file_exists(ROOT.'assets/image/icons/'.$type_name.'.jpg')) {
      $result = str_replace('EKA-', '', $type_name);
      $result = '<span style="font-style: italic; font-size: 10px; color: grey">'.$result.'</span>';
      return $result;
    }
    return '<img src="'.HOST.'assets/image/icons/'.$type_name.'.jpg" style="width: 20px; height: 20px; position: relative" />';
}

function showNewCustomer($date, $size = 30)
{
  $element = explode(' ', $date);
  $diff = timeSpend($element[0], date('Y-m-d'),'%R%a');
  $nbDay = str_replace('+', '', $diff);
  if($nbDay < 90) {
    return '<img src="'.HOST.'assets/image/icons/new_child.png" style="width: '.$size.'px; height: '.$size.'px; position: relative" />';
  }

}

function showNewCustomerColor($date, $size = 30)
{
  $element = explode(' ', $date);
  $diff = timeSpend($element[0], date('Y-m-d'),'%R%a');
  $nbDay = str_replace('+', '', $diff);
  if($nbDay < 90) {
    return 'backgroundGreenNew';
  }
}

function randomValueCache($separator = '?') {
  return $separator.sha1(rand(1,100000));
}




