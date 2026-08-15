<?php

$GLOBALS['translation_terms'] = parse_ini_file(APPLICATION.'Translation.ini', true);


function trans($term)
{
  $datas = $GLOBALS['translation_terms'];
  if(!array_key_exists($term, $datas)) return $term;
  return $datas[$term];
}


