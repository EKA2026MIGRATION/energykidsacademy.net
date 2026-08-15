<?php


function showUserInfo()
{
  echo '<pre>'; print_r($_SESSION['PERSON_CONNECTED']); echo '</pre>';
}

function getCurrentStaffData() {
  return $_SESSION['PERSON_CONNECTED']['STAFF'];
}

function getCurrentStaffId()
{
  return $_SESSION['PERSON_CONNECTED']->staff->staffId;
}


/**
 * @param String|Array
 * @return Bool
 */
function hasRole($role) {

  if($_SESSION['ROLE'] == 'ROLE_ADMIN') return true;
  if($_SESSION['ROLE'] == 'ROLE_SUPER_ADMIN') return true;

  // create role passed to valid
  if(is_array($role)) {
    foreach($role as $r) {
      $r = str_replace('ROLE_', '', $r); $roles[] = 'ROLE_'.$r;
    }
  } else {
    $r = str_replace('ROLE_', '', $role);
    $roles[] = 'ROLE_'.$role;
  }

  // checked if the roles passed is in the session_role
  foreach($roles as $r) {
    if(in_array($r, $_SESSION['ROLE'])) $checked = 1;
  }
  if(!isset($checked)) return false;
  return true;
}

/**
 * @return String
 */
function showRoles() {
  return implode(' | ', $_SESSION['ROLE']);
}

/**
 * @return Array
 */
function getRoles() {
  return $_SESSION['ROLE'];
}
