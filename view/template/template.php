<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">

    <title><?php echo $title; ?></title>


    <?php if(isset(FILES_ROUTE[ROUTE])) { foreach(FILES_ROUTE[ROUTE]['css'] as $item){
    if($item != "") {?>
      <link rel="stylesheet" href="<?= CSS;?><?php echo $item; ?>">
    <?php } } } ?>
    <link rel="stylesheet" href="<?= CSS;?>toast.min.css">
    <link rel="stylesheet" href="<?= CSS;?>styles.css">
    <link rel="stylesheet" href="<?= CSS;?>app.css">
    <link rel="stylesheet" href="<?= CSS;?>animate.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="<?= IMG; ?>favicon.png">
    
    <script src="<?= JS;?>vendor/jquery.js"></script>

      <style>
          .myModal {
              display: none;
              position: fixed;
              z-index: 99;
              left: 0;
              top: 0;
              width: 100%;
              height: 100%;
              overflow: auto;
              background-color: rgb(0,0,0);
              background-color: rgba(0,0,0,0.4);
          }

          .myModal-content {
              background-color: #fefefe;
              margin: 15% auto;
              padding: 20px;
              border: 1px solid #888;
              width: 50%;
          }

          .close-btn {
              color: #aaa;
              float: right;
              font-size: 28px;
              font-weight: bold;
          }

          .close-btn:hover,
          .close-btn:focus {
              color: black;
              text-decoration: none;
              cursor: pointer;
          }

          #lienJustificatif:hover {
                text-decoration: underline;
          }

      </style>

  </head>
  <body>


  <div class="loading" style="display: none;">Loading&#8230;</div>
  <!-- Accessibilité : liens d'évitement (utiles pour les personnes aveugles qui n'aviguent au clavier) -->
  <ul class="skip-links">
    <li><a href="#navigation">Aller à la navigation</a></li>
    <li><a href="#main">Aller au contenu</a></li>
  </ul>

  <body class="not-front">



    <header role="banner" class="site-header">
      <div class="site-header-wrapper">
        <div class="site-branding">
          <!-- Logo et titre du site -->
          <div class="site-logo"><!-- @TOPROD: Le logo est dans un <h1> uniquement sur la page d'accueil. Sur les pages internes, le logo doit être dans un <div>. -->
            <a class="site-logo-link" href="<?= HOST ?>">
              <img src="<?= IMG ?>energy-kids-academy.svg" alt="Energy Kids Academy - On a tout le temps de grandir" width="127" height="127">
            </a>
            <span class="site-logo-name">
              <span class="site-logo-title">Energy Kids Academy</span>
              <span class="site-logo-slogan">Enseignement sportif <br>pour enfant</span>
            </span>
          </div>

          <button class="btn-menu-burger js-menuburger">
            <span class="icon-menu" aria-hidden="true"></span>
            <span class="visually-hidden">Menu</span>
          </button>
        </div><!-- /site-branding -->

        <?php if(isset($_SESSION['TOKEN'])) { include_once('navigation_connected.php'); } else { include_once('navigation_disconnected.php'); }?>


      </div><!-- /site-header-wrapper -->
    </header>

    <div class="site-wrapper" style="background-image: url('<?= IMG ?>background-photo-foot.jpg');">

      <main id="main" class="site-main">
        <div class="site-main-banner">
            <div class="title-with-lovely-clouds">
              <h1 class="site-main-title"><span><?php echo (isset($h1)) ? $h1 : $title ?></span></h1>
              <!-- Des bouts de nuages de décoration -->
              <span class="lovely-clouds-left"><!-- empty --></span>
              <span class="lovely-clouds-right"><!-- emtpy --></span>
            </div>
        </div><!-- /site-main-banner -->
      <?php if(isset($_SESSION['TOKEN'])): ?>
        <input type="hidden" id="isConnected" value="yes">
        <div id="cartViewText" title="voir le panier">
          <label id="clickToViewCart" class="btn" for="modal-cart">
            <i class="material-icons" style="font-size: 1.5rem;">shopping_cart</i>&nbsp;&nbsp;Mon panier
          </label>
          <div id="cartViewInfo" onclick="viewCartClick()">
            <div style="text-align: center">
                <span class="nbRegistration" id="showNbRegHeader">
                  <img src="<?= IMG ?>loading-buffering.gif" width="20" height="20" alt="">
                </span>
                Inscription(s)
            </div>
          </div>
        </div>
        <div id="cartViewInfoFloat" onclick="clickToViewCart()" style="display: none">
          <span class="nbRegistration" id="showNbRegFloat"></span>
          <i class="material-icons">shopping_cart</i>           
        </div>
      <?php else: ?>
           <input type="hidden" id="isConnected" value="no">
      <?php endif; ?>




<input class="modal-state" id="modal-cart" type="checkbox" />
<div class="modal">
  <label class="modal__bg" for="modal-cart"></label>
  <div class="modal__inner" id="modal-cart-content">
    <label class="modal__close" for="modal-cart" style="background-color: darkblue; padding: 20px"></label>
    <h2>Panier</h2>
    <div id="contentCart"></div>
  </div>
</div>



        <!-- Contenu principal de la page -->
        <div class="main-content">

          <?= $contentPage ?>

        </div><!-- /main-content -->
      </main>

      <!-- Pied de page -->
<footer role="contentinfo" class="site-footer">

      <div class="footer-certifications">
        <h2 class="footer-certifications-title">Certifications et Homologations</h2>

        <ul class="certification-list grid-list unstyled">
          <li class="certification-item">
            <div class="certification-illust">
              <img src="<?= IMG ?>certif-dreia.png" width="60" height="60" alt="">
            </div>
            <div class="certification-content">DREIA n° 0000247<br>
            Club inscrit au Registre des Transporteurs Routiers de Personnes</div>
          </li>
          <li class="certification-item">
            <div class="certification-illust">
              <img src="<?= IMG ?>certif-fft.png" width="60" height="60" alt="">
            </div>
            <div class="certification-content">FFT n° 31910504<br>
            Club affilié à la Fédération Française de Tennis.</div>
          </li>
          <li class="certification-item">
            <div class="certification-illust">
              <img src="<?= IMG ?>certif-js.png" width="60" height="60" alt="">
            </div>
            <div class="certification-content">DDJS n° ET000047<br>
            Club déclaré à la Direction Départementale de la Jeunesse et des Sports.</div>
          </li>
        </ul>
      </div><!-- /section-certifications -->

      <div class="footer-misc grid">
        <!-- Contact (tél et email) -->
        <div class="footer-contact two-thirds">
          <h2 class="section-title-small">Contact</h2>

          <div class="footer-contact-wrapper">
            <div class="footer-contact-infos">
              <p class="footer-contact-tel">01 47 01 59 60</p>
              <p class="footer-contact-mail"><a href="mailto:contact@energykidsacademy.net">contact@energykidsacademy.net</a></p>
            </div>
            <div class="footer-contact-pins">
              <p class="footer-contact-pin blue">Un <strong>club</strong> de 2 hectares aux portes de Paris à 20 minutes de la Tour Eiffel</p>
              <p class="footer-contact-pin red">Trois <strong>gymnases</strong> couverts et chauffés au coeur de Paris</p>
            </div>
          </div><!-- /footer-contact-wrapper -->
        </div><!-- /footer-contact -->
        <!-- Liste d'actualités -->
        <div class="footer-news one-third">
        <h2 class="section-title-small">Actualités sur facebook</h2>
       <!--debut facebook plugin page-->

       <!--
       <div class="txtcenter">
        <div id="fb-root" class=" fb_reset"><div style="position: absolute; top: -10000px; width: 0px; height: 0px;"><div><iframe name="fb_xdm_frame_https" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" allow="encrypted-media" id="fb_xdm_frame_https" aria-hidden="true" title="Facebook Cross Domain Communication Frame" tabindex="-1" src="https://staticxx.facebook.com/connect/xd_arbiter/r/j-GHT1gpo6-.js?version=43#channel=f117d7bb5cfd1c8&amp;origin=https%3A%2F%2Fwww.energykidsacademy.fr" style="border: none;"></iframe></div><div></div></div></div>
    <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.8&appId=189712161075617";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        <div class="fb-page fb_iframe_widget" data-href="https://www.facebook.com/EnergyKidsAcademy/" data-tabs="timeline" data-width="250" data-height="210" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" fb-xfbml-state="rendered" fb-iframe-plugin-query="adapt_container_width=true&amp;app_id=189712161075617&amp;container_width=280&amp;height=210&amp;hide_cover=false&amp;href=https%3A%2F%2Fwww.facebook.com%2FEnergyKidsAcademy%2F&amp;locale=fr_FR&amp;sdk=joey&amp;show_facepile=true&amp;small_header=false&amp;tabs=timeline&amp;width=250"><span style="vertical-align: bottom; width: 250px; height: 210px;"><iframe name="fb0a3f257c4f14" width="250px" height="210px" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" allow="encrypted-media" title="fb:page Facebook Social Plugin" src="https://www.facebook.com/v2.8/plugins/page.php?adapt_container_width=true&amp;app_id=189712161075617&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter%2Fr%2Fj-GHT1gpo6-.js%3Fversion%3D43%23cb%3Df2ed59641a8a2ac%26domain%3Dwww.energykidsacademy.fr%26origin%3Dhttps%253A%252F%252Fwww.energykidsacademy.fr%252Ff117d7bb5cfd1c8%26relation%3Dparent.parent&amp;container_width=280&amp;height=210&amp;hide_cover=false&amp;href=https%3A%2F%2Fwww.facebook.com%2FEnergyKidsAcademy%2F&amp;locale=fr_FR&amp;sdk=joey&amp;show_facepile=true&amp;small_header=false&amp;tabs=timeline&amp;width=250" style="border: none; visibility: visible; width: 250px; height: 210px;" class=""></iframe></span></div>
        </div>

        -->

        <!--fin facebook plugin page-->

        <!--<h2 class="section-title-small">Actualités</h2>
          <ul class="footer-news-list unstyled">
            <li class="footer-news-item">
              <a href="#" class="footer-news-link">Titre d'une actualité ici</a>
            </li>
            <li class="footer-news-item">
              <a href="#" class="footer-news-link">Autre titre d'une actualité plus longue</a>
            </li>
            <li class="footer-news-item">
              <a href="#" class="footer-news-link">Titre d'une actualité ici</a>
            </li>
            <li class="footer-news-item">
              <a href="#" class="footer-news-link">Titre d'une actualité ici</a>
            </li>
          </ul>
          <p class="mtm footer-news-btn"><a class="btn-primary" href="#">Découvrez le blog</a></p>-->
        </div><!-- /footer-news -->
      </div><!-- /footer-misc -->

      <nav class="footer-nav">
        <!-- Footer 1rst navigation -->
        <ul class="footer-nav-list unstyled">
          <li class="footer-nav-item">
            <a href="https://www.energykidsacademy.fr/mentions-legales.html" class="footer-nav-link">Mentions légales</a>
          </li>
          <li class="footer-nav-item">
            <a href="https://www.energykidsacademy.fr/protection-donnees.html" class="footer-nav-link">Protection des données</a>
          </li>
          <li class="footer-nav-item">
            <a href="https://www.energykidsacademy.fr/conditions-generales-utilisation.html" class="footer-nav-link">CGU</a>
          </li>
        </ul><!-- /footer-nav-list -->

        <span class="footer-nav-decoration"><span class="icon-star" aria-hidden="true"><!-- Élément vide de décoration --></span></span>

        <!-- Footer 2nd navigation -->
        <ul class="footer-nav-list unstyled">
         <li class="footer-nav-item">
            <a href="https://www.energykidsacademy.fr/conditions-generales-vente.html" class="footer-nav-link">CGV</a>
          </li>
          <li class="footer-nav-item">
            <a href="https://www.energykidsacademy.fr/plan-site.html" class="footer-nav-link">Plan du site</a>
          </li>
          <li class="footer-nav-item">
            <a href="https://www.energykidsacademy.fr/recrutement.html" class="footer-nav-link">Recrutement</a>
          </li>
          <li class="footer-nav-item">
            <a href="https://www.energykidsacademy.fr/partenaires.html" class="footer-nav-link">Partenaires</a>
          </li>
        </ul><!-- /footer-nav-list -->
      </nav><!-- /footer-nav -->
    </footer>
    </div><!-- /site-wrapper -->


    <!-- Modal pour le justificatif de domicile -->
    <?php if(isset($_SESSION['TOKEN'])):?>
        <div class="myModal" id="modalJustificatif" style="">
            <?php foreach(PERSON_CONNECTED['children'] as $child) { $childIdList[] = $child['childId'];};?>
            <div class="myModal-content" style="text-align: center">
                <span class="close-btn" id="closeModalJustificatif">&times;</span>
                <h2>Ajouter vos documents personnels</h2>
                <form action="<?= HOST;?>utilisateur/ajouter-un-justificatif" method="post" enctype="multipart/form-data">
                    <div style="display: flex; align-items: center">
                        <div style="width: 250px">Justificatif de domicile</div>
                        <?php if( PERSON_CONNECTED['children'][0]['frontDocument'] != ""):?>
                            <a href="<?= PERSON_CONNECTED['children'][0]['frontDocument'];?>" target="_blank">Voir le document</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <span style="color: darkred; font-weight: bold" onclick="removeJustificatif('frontDocument')" title="Supprimer">X</span>
                        <?php else:?>
                            <input type="file" name="justificatif" id="justificatif">
                        <?php endif;?>
                    </div>

                    <div style="display: flex; align-items: center">
                        <div style="width: 250px">QR CODE JO 2024</div>
                        <?php if( PERSON_CONNECTED['children'][0]['frontQr'] != ""):?>
                            <a href="<?= PERSON_CONNECTED['children'][0]['frontQr'];?>" target="_blank">Voir le document</a>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <span style="color: darkred; font-weight: bold" onclick="removeJustificatif('frontQr')" title="Supprimer">X</span>
                        <?php else:?>
                            <input type="file" name="qrcode" id="qrcode">
                        <?php endif;?>
                    </div>
                    <br/><br/>
                    <div>
                        <input type="submit" value="Envoyer">
                    </div>
                </form>
            </div>
        </div>
    <?php endif;?>



    <input type="hidden" id="urlApi" value="<?= API ?>">
    <input type="hidden" id="urlPhoto" value="<?= URL_PHOTO ?>savePhoto">
    <input type="hidden" id="urlRequest" value="<?= HOST ?>sendRequest">
    <input type="hidden" id="urlHost" value="<?= HOST ?>">
    <input type="hidden" id="mapsApiKey" value="<?= MAPS_API_KEY ?>">
    <input type="hidden" id="photoProfilDefault" value="<?= IMG ?>no_photo.jpg">
    <input type="hidden" id="noPhoto" value="<?= IMG ?>no_photo_2.jpg">
    <input type="hidden" id="token" value="<?= TOKEN; ?>">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>
    <script src="<?= JS;?>vendor/toast.min.js"></script>
    <script src="<?= JS ?>global.min.js"></script>
    <script src="<?= JS;?>serializeToJson.js"></script>
    <script src="<?= JS;?>app.js"></script>

    <script type="text/javascript">
        // Récupérer le modal
        const modal = document.getElementById("modalJustificatif");

        // Récupérer le lien qui ouvre le modal
        const lienModal = document.getElementById("lienJustificatif");

        // Récupérer l'élément <span> qui ferme le modal
        const span = document.getElementById("closeModalJustificatif");

        // Quand l'utilisateur clique sur le lien, ouvre le modal

        lienModal.addEventListener('click', function() {
            modal.style.display = "block";
        });

        // Quand l'utilisateur clique sur <span> (x), ferme le modal
        span.addEventListener('click', function() {
            modal.style.display = "none";
        });

        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });

        const removeJustificatif = (doc, idlist) => {

        }


    </script>

    <?php
    // AUTLOAD FILES JS -> REDUCTION TEMPS CHARGEMENTS
    if(isset(FILES_ROUTE[ROUTE])) { foreach(FILES_ROUTE[ROUTE]['js'] as $item){
    if($item != "" && $item != "google-maps") {?>
      <script src="<?= JS;?><?php echo $item; ?>"></script>
    <?php }
    elseif($item == "google-maps")
    {
    ?>
      <script src="https://maps.googleapis.com/maps/api/js?key=<?= MAPS_API_KEY ?>&libraries=places"></script>
    <?php
    } } }


    ?>


  </body>
</html>
