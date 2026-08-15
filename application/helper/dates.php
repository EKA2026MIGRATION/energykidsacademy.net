<?php

/**
 *
 * List of functions HTML / FORMAT
 * used only in View templates
 *
 **/


/**
 * Show date with new format
 * @param string
 * @return string
 */
function showDate($date, $format = 'd/m/Y') {
  $dateArray = explode('|', $date);

  if(count($dateArray) > 1) {
    $dateFormatted = '';
    foreach($dateArray as $dateSingle) {
      if (!isDate($dateSingle)) {
        continue;
      }
      $myDate = new DateTimeFrench($dateSingle);

      if($dateFormatted == '') {
        $dateFormatted .= $myDate->format($format);
      } else {
        $dateFormatted .= '-'.$myDate->format($format);
      }
      
    }
    return $dateFormatted;

  } else {
    $date = $dateArray[0];
  }

  if (!isDate($date)) {
    return '';
  }

  $myDate = new DateTimeFrench($date);
  return $myDate->format($format);
}


function showHour($hour) {
  if(strlen($hour)>10) return null;

  $time = explode(':', $hour);
  return $time[0].':'.$time[1];
}

function isDate($date)
{
   return (DateTime::createFromFormat('Y-m-d', $date) !== false);
}

/**
 * return month
 * @param string
 * @return string
 */
function getMonth($date) {
  return showDate($date, 'F');
}

/**
 * return year of date
 * @param string
 * @return string
 */
function getYear($date, $format = 'Y') {
  return showDate($date, $format);
}

/**
 * return week of date
 * @param string
 * @return string
 */
function getWeek($date, $format = 'W') {
  return showDate($date, $format);
}

/**
 * return time H:m
 * @param string
 * @return string
 */
function showTime($time, $format = 'H:i')
{
  return showDate($time, $format);
}

/**
 * return difference time
 * @param string
 * @return string
 */
function timeSpend($start, $end, $format = '%H:%I')
{
  $datetime1 = new DateTime($start);
  $datetime2 = new DateTime($end);
  $interval = $datetime1->diff($datetime2);
  return $interval->format($format);
}

/**
 * return difference date
 * @param string
 * @return string
 */
 function diffDate($start , $end )
 {
     $date1 = showDate($start, 'Ymd');
     $date2 = showDate($end, 'Ymd');
     return $date2-$date1;

 }

function convertTimeSpend($seconds) {
  return $seconds;
  $minutes = $seconds/60;
  $hours   = $minutes/60;
  $days    = $hours/24;
  return $days.' '.$hours.' '.$minutes;
}

function getDateStartWeek($date_ref = null) {
    if(!$date_ref) $date_ref = date('Y-m-d');
    // Calcul de l'écart entre le jour de $day et le lundi (=1)
    $rel = 1 - date('N', strtotime ($date_ref));
    //calcul du lundi avec cet écart
    $monday = date('Y-m-d', strtotime("$rel days", strtotime($date_ref)));
    return $monday;
}

function getDateEndWeek($date_ref = null) {
  $monday = getDateStartWeek($date_ref);
  $sunday = nextDay($date_ref, 6);
  return $sunday;
}

function getStartMonth($date, $format = "Y-m-d") {
    $startMonth = showDate($date, 'Y').'-'.showDate($date, 'm').'-01';
    return showDate($startMonth, $format);
}

function nextDay($date_ref, $n = 1)
{
  return date('Y-m-d', strtotime($date_ref.", +".$n." day"));
}

function prevDay($date_ref, $n = 1)
{
  return date('Y-m-d', strtotime($date_ref.", -".$n." day"));
}

function addHour($date, $format = "H:i:s", $val = 2) {
    $current_date = new DateTime($date);
    $new_date = $current_date;
    $new_date->modify('+'.$val.' hours');
    return $new_date->format($format);
}

function lessHour($date, $format = "H:i:s", $val = 1) {
  $current_date = new DateTime($date);
  $new_date = $current_date;
  $new_date->modify('-'.$val.' hour');
  return $new_date->format($format);
}

function showDatePickerNavigation($endPoint, $date, $currentActiveStaffId = null)
{

  if($currentActiveStaffId) {
    $prevLeftLink = HOST.$endPoint.'/'.prevDay($date).'/idDriver/'.$currentActiveStaffId.'/';
    $nextRightLink = HOST.$endPoint.'/'.nextDay($date).'/idDriver/'.$currentActiveStaffId.'/';

  } else {
    $prevLeftLink = HOST.$endPoint.'/'.prevDay($date)."/";
    $nextRightLink = HOST.$endPoint.'/'.nextDay($date)."/";
  }
  include(VIEW.'render/blockTemplate/_datePickerNavigation.php');
}

function showDuration($duration) {
  $element = explode(':', $duration);
  $html = "";
  if($element[0] != '00') {
    $html .= ' '.$element[0].' jour';
    if($element[0] != '01') $html .= 's';
  }
  if($element[1] != '00') {
    $html .= ' '.$element[1].' heure';
    if($element[1] != '01') $html .= 's';
  }
  if($element[2] != '00') $html .= ' '.$element[2].' minutes ';
  return $html;
}

function newDiffDate($start, $end) {
    $date1 = new DateTime($start);
    $date2 = new DateTime($end);
    $interval = $date1->diff($date2);
    return intval($interval->format('%a'));
}