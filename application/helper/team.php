<?php

$GLOBALS['teams_trans'] = [1 => 'coach', 2 => 'driver', 3 => 'maintenance', 4 => 'secrétariat', 5 => 'TIC'];

function showTeamsStaff($array) {
    $teamsname = $GLOBALS['teams_trans'];
    $idList = explode(',', $array);
    foreach($idList as $id) {
      if($id > 0 && key_exists($id, $teamsname)) $result[] = $teamsname[$id];
    }

    return implode(', ', $result);
}

function inTeam($idList, $searchedTeam) {
    $teamsUserArray = explode(',', $idList);
    $allTeamsId = array_flip($GLOBALS['teams_trans']);

    if(!isset($allTeamsId[$searchedTeam])) return false;

    $searchedTeamId = $allTeamsId[$searchedTeam];

    if(!in_array($searchedTeamId, $teamsUserArray)) return false;
    return true;
}