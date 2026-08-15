<i class="material-icons" id="closeConfirmButton" style="float: right; cursor: pointer; margin-right: 30px; font-size: 40px">
  close
</i>

<br style="clear: both"/>

<div class="infoPaiementInformation">
		<i class="material-icons">credit_card</i>
		<div>
			Nous vous rappelons qu'une inscription n'est enregistrée et validée<br/>
			qu'accompagnée de son règlement par carte bancaire.
		</div>
</div>
<div class="infoPaiementInformation">
		<i class="material-icons">mail_outline</i>
		<div>
			L'inscription réglée, une confirmation de transaction et<br/>
			un récapitulatif d'inscription sont envoyés par mail.
		</div>
</div>


<ul id="paiement-cgv" style="width:80%; margin: 0 auto">
    <br/>
    <li>
        <input type="checkbox" class="checkboxRGPD checkboxRequired"/>&nbsp;&nbsp;&nbsp;
        En validant mon inscription, je déclare avoir pris connaissance et accepté sans réserve <a href="https://www.energykidsacademy.fr/conditions-generales-vente.html" target="_blank">les conditions générales de vente</a>
        <sup>*</sup>
    </li>
    <br/>
    <li>
        <input type="checkbox" class="checkboxRGPD checkboxRequired">&nbsp;&nbsp;&nbsp;
        En cochant la présente case, je demande l’exécution du service avant l’expiration du <b>délai de rétractation</b> de 14 jours, et reconnais donc que je ne bénéficie d’aucun droit de rétractation pour les services pleinement exécutés dans les 14 jours.
        <sup>*</sup>
    </li>
    <br/>
    <li>
        <input type="checkbox" class="checkboxRGPD"/>&nbsp;&nbsp;&nbsp;
        J'autorise ENERGY KIDS ACADEMY à prendre des photos / des vidéos de mon enfant durant les différentes activités sportives. Ces photos/vidéos sont diffusées régulièrement à l’ensemble des parents d’élèves aux fins d’informations, de souvenirs et de comptes-rendus sur les activités.
   </li>
        <br/>
        <li style="font-style: italic; color: #D0112B; font-size: 14px">
            <sup>*</sup> cases à cocher obligatoire
        </li>
</ul>


<div class="form-submit" id="commandSubmit" style="visibility: hidden">
  <input type="submit" class="btn-primary with-icon" value="Commander - <?= $params->priceTotal ?> €">
  <br/>
  <br/>
  <a href="<?= HOST;?>" class="btn-primary" style="font-size: 10px; background-color: darkblue; line-height: 2.2;">ANNULER</a>
</div>

<script>

$('#confirmButton').click(function() {

  $('#infoPaiement').show();
          $('#confirmButton').hide();
          $('#modal-cart-content').animate({
                scrollTop: $("#targetScroll").offset().top
          }, 500);

})

$('#closeConfirmButton').click(function() {

	$('#infoPaiement').hide();
	$('#confirmButton').show();
	$('#modal-cart-content').animate({
        scrollTop: $("#form-cart").offset().top
    }, 500);
})

$('.checkboxRGPD').click(function(){
  var nb = 0;
  $('.checkboxRequired').each(function(index) {
    let val = $(this).is(':checked');
    if (val == true) {
      nb = 1 + nb;
    }
  });
  if(nb == 2) {
    $('#commandSubmit').css("visibility", "visible");
  } else {
    $('#commandSubmit').css('visibility', "hidden");
  }

})

</script>
