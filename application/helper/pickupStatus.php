<?php

/**
 *
 * List of functions HTML / FORMAT
 * used only in View templates
 *
 **/

function showIconStatus($status, $lastStatus) {

   
    if($status == "pec")
    {
      echo '<i class="material-icons status olive">check</i>';
    }
    elseif($status == "npec")
    {
      echo '<i class="material-icons status red">close</i>';
    }
    else // Status = null
    {
      echo '<i class="material-icons status blue">access_time</i>';
    }

}
