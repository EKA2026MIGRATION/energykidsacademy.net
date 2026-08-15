<?php
/**
 *
 * List of functions HTML / FORMAT
 * used only in View templates
 *
 **/

/**
 * Show li without meal
 * @param string
 * @return string
 */
function liWithoutMeal($date, $childname, $id, $type, $photo_url = null) {
  $html = '';
  $html .= '<li>
      <a href="'.HOST.'meal/add/callback/meal-list/date/'.$date.'/'.$type.'/'.$id.'/">
          <div>
              <p class="list-header">
                  <img src="'.$photo_url.'" class="width-30 height-30" height="" width="" alt="" />
                  '.$childname.'
                  <div class="with-icon">
                      <i class="material-icons">add</i>
                  </div>
              </p>
          </div>
      </a>
  </li>';
  return $html;

}
