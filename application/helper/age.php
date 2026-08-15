<?php

/**
 *
 * List of functions HTML / FORMAT
 * used only in View templates
 *
 **/


/**
 * Show age from birth date
 * @param string
 * @return string
 */
function showAge($bithdayDate, $showText = true) {
   $date = new DateTime($bithdayDate);
   $now = new DateTime();
   $interval = $now->diff($date);
   ($showText == true) ? $text = " ans" : $text = "";
   return $interval->y.$text;
}


