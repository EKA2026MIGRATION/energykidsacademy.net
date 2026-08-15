/**!
 Global JS
 */

$(document).ready(function() {

  /*
   * Swiper JS — iDangerous
   * @link: http://idangero.us/swiper/
   * Documentation: http://idangero.us/swiper/api/#.WHX7L7bhAQ8
   * Démos: http://idangero.us/swiper/api/#.WHX7L7bhAQ8
   */
  var swiper = [];

  /*
   * Swiper appliqué sur desktop et mobile, à l'aide de la class .js-swiper
   */
  $('.js-swiper').each(function(index) {

    var $el = $(this);

    swiper[index] = $el.swiper({
      slidesPerView: 1,
      autoplay: true,
      nextButton: '.js-swiper-control-next', // bouton "suivant"
      prevButton: '.js-swiper-control-prev', // bouton "précédent"
      pagination: '.js-swiper-pagination',   // pagination
      paginationClickable: true,
      paginationBulletRender: function (swiper, index, className) {
        return '<span class="swiper-pagination-item ' + className + '"><span class="visually-hidden">Item ' + (index + 1) + '</span></span>';
      },
      loop: true,
      a11y: true
    });

    // on cache le bouton "play" sur l'autoplay est activé par défaut sur le slider
    if (swiper[index].params.autoplaying = true) {
      $(this).find('.js-swiper-control-play').addClass('is-hidden');
    }

    // bouton pour stopper le défilement automatique
    $(this).find('.js-swiper-control-pause').on('click', function(e){
        swiper[index].stopAutoplay();
        $(this).addClass('is-hidden');
        $('.js-swiper-control-play').removeClass('is-hidden');
    });
    // bouton pour relancer le défilement automatique
    $(this).find('.js-swiper-control-play').on('click', function(e){
        swiper[index].startAutoplay();
        $(this).addClass('is-hidden');
        $('.js-swiper-control-pause').removeClass('is-hidden');
    });

  });

  /*
   * Swiper appliqué uniquement sur petit écran (<640px), à l'aide de la class .js-m-swiper ("m" = mobile)
   */
  if (window.matchMedia("(max-width: 640px)").matches) {

    $('.js-m-swiper').each(function(index) {

      var $el = $(this);

      swiper[index] = $el.swiper({
        slidesPerView: 'auto',
        nextButton: '.js-swiper-control-next', // bouton "suivant"
        prevButton: '.js-swiper-control-prev', // bouton "précédent"
        pagination: '.js-swiper-pagination', // pagination
        paginationClickable: true,
        paginationBulletRender: function (swiper, index, className) {
          return '<span class="swiper-pagination-item ' + className + '"><span class="visually-hidden">Item ' + (index + 1) + '</span></span>';
        },
        loop: false,
        spaceBetween: 15,
        a11y: true
      });

    });

  }


  /*
   * Compteur animé au scroll
   * D'après le script : https://github.com/bfintal/Counter-Up
   */
  $('.js-counter').counterUp({
    delay: 5,
    time: 1000
  });


  /*
   * Main navigation (niveau 2)
   * Ajout d'une class "non-hover" sur tous les liens du niveau 2 sauf celui qui est survolé (afin d'appliquer un style CSS aux autres liens)
   */
  $('.subnavigation-link').hover(function() {
    $('.subnavigation-link').addClass('non-hover');
    $(this).removeClass('non-hover');
  });


  /*
   * Main navigation (niveau 2)
   * On cache le sous-menu le temps de quelques secondes pour éviter qu'il s'affiche au chargement de la page
   */
  // Visual hack to avoid CSS3 animation on window resize
  var resizeTimer;

  // Hide .js-accessible-navigation on page loading
  $('.js-nav-system__subnav').hide();

  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(function() {

    // Show .js-accessible-navigation when the page is loaded
    $('.js-nav-system__subnav').show();

  }, 50);

});
