<!-- Navigation principale -->
<div id="navigation" class="site-navigation">

      <nav role="navigation" class="navigation">
          <ul id="registration-navigation" class="navigation-list js-nav-system">

              <li class="navigation-item js-nav-system__item" data-show-sub="false">
                  <a class="navigation-link js-nav-system__link" href="#">Espace perso</a>

                  <!-- Navigation 2nd niveau -->
                  <ul class="subnavigation-list js-nav-system__subnav" data-visually-hidden="true" style="display: block;">
                      <li class="subnavigation-item-title js-nav-system__subnav__item" style="text-transform: uppercase">Espace perso</li>

                      <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link non-hover" href="<?= HOST ?>utilisateur/mon-compte">Mes accès</a>
                      </li>

                      <li class="subnavigation-item js-nav-system__subnav__item">
                          <!--<a class="subnavigation-link js-nav-system__subnav__link non-hover" href="<?= HOST ?>utilisateur/mes-profils">Profil(s)</a>-->
                          <a class="subnavigation-link js-nav-system__subnav__link non-hover" href="<?= HOST ?>utilisateur/profil/d/<?= encodeInt(PERSON_CONNECTED['personId']); ?>/">Mon Profil</a>
                      </li>
<!--
                      <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link non-hover" href="<?= HOST ?>utilisateur/profils-associes">Profil(s) associé(s)</a>
                      </li>-->
                      <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link non-hover" href="<?= HOST ?>utilisateur/mes-photos">Mes photos</a>
                      </li>
                      <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link non-hover" href="<?= HOST ?>utilisateur/mes-enfants">Mes enfant(s)</a>
                      </li>
                      <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link" href="<?= HOST ?>utilisateur/historique">Mon historique</a>
                      </li>
                      <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link" href="<?= HOST ?>utilisateur/paiement-en-attente">Mes paiements en attente</a>
                      </li>
                      <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link" href="<?= HOST ?>utilisateur/livrets">Livrets</a>
                      </li>

                  </ul>
                  <!-- /subnavigation-list -->
              </li>

          <hr/>

             <li class="navigation-item js-nav-system__item" data-show-sub="false">
                  <a class="navigation-link js-nav-system__link" href="<?= HOST; ?>blog/list">Le blog</a>
              </li>


              <?php if($_SESSION['canRegister'] == 1): ?>


                <hr/>

                <li class="navigation-item js-nav-system__item" data-show-sub="false">
                      <a class="navigation-link js-nav-system__link" href="#">Club avec transport</a>

                      <!-- Navigation 2nd niveau -->
                      <ul class="subnavigation-list js-nav-system__subnav" data-visually-hidden="true" style="display: block;">
                        <li class="subnavigation-item-title js-nav-system__subnav__item">LE CLUB AVEC TRANSPORT</li>
                        <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link" href="<?=HOST ;?>ea/category/id/<?= encodeInt(10);?>,<?= encodeInt(11);?>/">Les cours à l'année</a>
                        </li>
                        <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link" href="<?=HOST ;?>ea/a-la-carte/id/<?= encodeInt(5);?>/">Stages vacances - à la séance</a>
                        </li>
                        <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link" href="<?=HOST ;?>ea/category/id/<?= encodeInt(4);?>,<?= encodeInt(6);?>,<?=encodeInt(9);?>/">Stages vacances - à la semaine</a>
                        </li>
                        <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link" href="<?=HOST ;?>ea/category/id/<?= encodeInt(3);?>/">Anniversaire</a>
                        </li>
                      
                      </ul><!-- /subnavigation-list -->
                </li>


              
              <?php if(isset($_SESSION['gymnases'])):?>


              <li class="navigation-item js-nav-system__item" data-show-sub="false">
                      <a class="navigation-link js-nav-system__link" href="#">Gymnases parisiens</a>

                      <!-- Navigation 2nd niveau -->
                      <ul class="subnavigation-list js-nav-system__subnav" data-visually-hidden="true" style="display: block;">
                        <li class="subnavigation-item-title js-nav-system__subnav__item">GYMNNASES PARISIENS</li>

                        <?php foreach($_SESSION['gymnases'] as $product):?>
                          <li class="subnavigation-item js-nav-system__subnav__item">
                            <a class="subnavigation-link js-nav-system__subnav__link" href="<?=HOST ;?>ea/category/id/<?= encodeInt(7);?>/p/<?= encodeInt($product->productId) ;?>/"><?= strip_tags($product->nameFr);?></a>
                          </li>
                        <?php endforeach;?>                      
                      </ul><!-- /subnavigation-list -->
              </li>

              <?php endif;?>


              <li class="navigation-item js-nav-system__item" data-show-sub="false">
                      <a class="navigation-link js-nav-system__link" href="#">Séjours vacances</a>

                      <!-- Navigation 2nd niveau -->
                      <ul class="subnavigation-list js-nav-system__subnav" data-visually-hidden="true" style="display: block;">
                        <li class="subnavigation-item-title js-nav-system__subnav__item">SÉJOURS VACANCES</li>
                        <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link" href="<?=HOST ;?>ea/category/id/<?= encodeInt(2);?>/">Les séjours de ski</a>
                        </li>
                        <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link" href="<?=HOST ;?>ea/category/id/<?= encodeInt(1);?>/">Les séjours en Corse</a>
                        </li>
                        
                      </ul><!-- /subnavigation-list -->
              </li>


              <li class="navigation-item js-nav-system__item" data-show-sub="false">
                      <a class="navigation-link js-nav-system__link" href="#">Sorties à thèmes</a>

                      <!-- Navigation 2nd niveau -->
                      <ul class="subnavigation-list js-nav-system__subnav" data-visually-hidden="true" style="display: block;">
                        <li class="subnavigation-item-title js-nav-system__subnav__item">SORTIES À THEMES</li>
                        <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link" href="<?=HOST ;?>ea/category/id/<?= encodeInt(12);?>/">Evènements sportifs</a>
                        </li>
                        <li class="subnavigation-item js-nav-system__subnav__item">
                          <a class="subnavigation-link js-nav-system__subnav__link" href="<?=HOST ;?>ea/category/id/<?= encodeInt(12);?>/">Parc d'attraction</a>
                        </li>
                        
                      </ul><!-- /subnavigation-list -->
              </li>


            <?php endif;?>


          </ul>
      </nav>

  <!-- 2nd navigation -->
  <ul class="contact-list unstyled">
    <li class="contact-item">
      <a class="contact-link" href="<?= HOST ?>auth/logout">Déconnexion</a>
    </li>
    <li class="contact-item">
      <a class="contact-link" href="https://www.energykidsacademy.fr/contact.html" target="_blank">Nous contacter</a>
    </li>
  </ul><!-- /subnavigation-list -->

  <!-- Menu langue -->
  <ul class="lang-list unstyled">
    <li class="lang-item">
      <a class="lang-link current" href="#" aria-label="Français" title="Français">FR</a>
    </li>
    <li class="lang-item">
      <a class="lang-link" href="#" aria-label="Anglais" title="Anglais">EN</a>
    </li>
  </ul><!-- /lang-list -->

        <!-- Réseaux sociaux -->
        <ul class="social-list unstyled">
          <li class="social-item">
            <a class="social-link" href="https://www.facebook.com/EnergyKidsAcademy/" aria-label="Facebook" title="Facebook" target="blank"><span class="icon-facebook"></span></a>
          </li>
          <li class="social-item">
            <a class="social-link" href="https://twitter.com/energy_academy" aria-label="Twitter" title="Twitter" target="blank"><span class="icon-twitter"></span></a>
          </li>
          <li class="social-item">
            <a class="social-link" href="https://plus.google.com/+energyacademy" aria-label="Google Plus" title="Google Plus" target="blank"><span class="icon-google"></span></a>
          </li>
        </ul><!-- /social-list -->
</div><!-- /site-navigation -->
