<?php
function railwayStationEffect($string, $class = "rSfx") {

    $string = str_replace(["é", "è"], "e",$string);


    $html = '';
    for($i = 0; $i < strlen($string); $i++) {

        $element = $string[$i];        

        if($element == " ") $element = "&nbsp;";

        $html .= '<span class="'.$class.'">'.$element.'</span>';
    }

    return $html;
}