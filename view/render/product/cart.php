<?php use_helper('dates'); ?>
<?php if ($params->nbOfRegistrations != 0): ?>
<?php $today = new DateTime();?>

<script src="<?= JS; ?>vendor/jquery.js"></script>
<form method="POST" id="form-cart" action="https://systempay.cyberpluspaiement.com/vads-payment/" style="padding-bottom: 30px">

	<!--
	<div class="form-submit btn-primary">
		<input type="submit" class="btn-primary with-icon" value="Commander (<?= $params->priceTotal; ?> €)">
	</div>
	--->

	<div class="wrapper active" style="width:95%; margin:auto; padding-bottom: 0px; ">


		<?php foreach ($params->myCart as $childId => $child): ?>

			<div style="display: flex; justify-content: space-between; flex-wrap: wrap">


			
				  <div style="text-align: center; margin-right: 20px; width: 180px; ">
				  	<?php ($child->photo != "") ? $img = URL_PHOTO.$child->photo : $img = IMG.'no_photo.jpg';?> 
					<img src="<?= $img ?>"  style="width: 160px; border-radius: 50%;"/>
					<br/>
					<b style="font-variant: small-caps slashed-zero; font-weight: bold; font-size: 24px"><?= $child->firstname;?></b>
					<br/>
				</div>
				
				<div class="details" style="width: 90%">
					<?php foreach($params->myProducts[$childId] as $products):?>
							<?php $i = 1; foreach($products as $sessions):?>
									<?php if($i == 1):?>
										<div style="color: darkblue; font-weight: bold; font-size: 24px;">	
											<?= $sessions['name'];?>
										</div>
										<?php $i = -1;?>
									<?php endif;?>
									<div id="cartItemRegisration<?= $sessions['registrationId'];?>" class="<?= $sessions['registrationId']; ?>"  style="font-style: italic; font-size: 19px">
										<div style=" display: flex; justify-content: space-between; flex-wrap: wrap">
											<div>
												<div class="close" onclick="deleteCartItem('<?= $sessions['registrationId']; ?>')" style="color: darkred; cursor: pointer;">
													<i class="material-icons">cancel</i>
												</div>
												<?php if($sessions['datesText'] && $sessions['datesText'] != ""):?>
													Dates 
													<?php if($sessions['datesText']):?>
														<?= $sessions['datesText'];?>
													<?php else :?>
														<?= $sessions['dates'];?>
													<?php endif;?>
												<?php else:?>
													<?= $sessions['description'];?>
												<?php endif;?>
												<br/>
												<?php if($sessions['sports']) echo 'Sport(s): '.$sessions['sports'].'<br/>';?>
												<?php if($sessions['localisation']) echo $sessions['localisation'].' - '.$sessions['transport'];?>
											</div>
											<div>
												<b><?= $sessions['amount'];?> €</b>
											</div>
										</div>
									</div>
									<br/>

							<?php endforeach;?>

					<?php endforeach;?>
				</div>
		
			</div>

			<hr/>
		<?php endforeach; ?>

	</div>

	<?php foreach ($params->systemPay as $nom => $valeur):?>
		<?php echo '<input type="hidden" name="'.$nom.'" value="'.$valeur.'" />'; ?>
	<?php  endforeach; ?>

	<input type="hidden" id="dateTransaction" value="<?= date('y-m-d h:i:s'); ?>">
	<input type="hidden" id="personTransaction" value="<?= PERSON_CONNECTED['personId']; ?>">
	<input type="hidden" id="amountTransaction" value="<?= $params->priceTotal; ?>">
	<input type="hidden" id="registrationIds" value="<?= $params->registrationIds; ?>">
	<input type="hidden" id="nbOfRegistrations" value="<?= $params->nbOfRegistrations; ?>">

	<br/><br/><br/>
	<div style="font-style: italic; color: grey;">
		Les informations recueillies par ENERGY KIDS ACADEMY sont obligatoires pour lui permettre d’exécuter et de traiter votre commande. Vous disposez d'un droit d’opposition, d'accès, de rectification et de suppression sur les données personnelles vous concernant, que vous pouvez exercer dans les conditions prévues par la loi du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés, conformément à  "la Charte de protection des données personnelles"  en envoyant un email à contact@energykidsacademy.net
	</div>
	<br/>

	<div class="form-submit">
		<div class="btn-primary with-icon" id="confirmButton">ACCEDEZ AU PAIEMENT<br/><?= $params->priceTotal; ?> €</div>
		<div id="infoPaiement" style="width: 80%; margin: auto auto; padding-top: 30px; margin-bottom: 30px; padding-bottom: 30px; border-radius: 30px; background-color: white; border: 4px solid darkred; display: none">
			<?php include '_infoPaiement.php'; ?>
		</div>
		<div id="targetScroll"></div>
	</div>
</form>

<input type="hidden" id="currentInvoiceId" value="<?= $params->systemPay['vads_order_id'];?>"/>
<div id="updateRegistration"></div>

<script>


$( "#form-cart" ).submit(function( event ) {


	let date = $("#dateTransaction").val();
	let internalOrder = $("[name=vads_order_info]").val();
	let status = "process";
	let number = $("[name=vads_order_info]").val();
	let person = $("#personTransaction").val();
	let amount = $("#amountTransaction").val();
	let invoice = $("[name=vads_order_id]").val();
	let nbOfRegistrations = $("#nbOfRegistrations").val();
	let registrationsIds = $("#registrationIds").val();
	let registrations = [];


	let currentInvoiceId = $('#currentInvoiceId').val();

	let myUrl = urlHost+"create/registration/invoiceId/"+currentInvoiceId+"/";

	$('#updateRegistration').load(myUrl);

	if(nbOfRegistrations > 1)
	{
		let splitRegistrationsIds = registrationsIds.split(',');

		splitRegistrationsIds.forEach(function(registrationId, i=0) {

		  registrations[i] = {registrationId};
		  i++;

		});
	}
	else
	{
		registrations[0] = {registrationId:registrationsIds};
	}

    let data = {internalOrder, status, number, person, invoice, registrations, amount};

    let url = "transaction/create";
    let type = "POST";
    $.ajax({
      type: "POST",
      url: urlRequest,
      data: {type, url, data},
      dataType: "json",
      beforeSend() {
		sessionStorage.setItem('urlRequest', urlRequest);
		console.log('before');

      },
      success(json) {
		// send to system pay
		console.log('sucess');
      	$('#form-cart').unbind('submit').submit();

      }
  	});


});
</script>



<?php else: ?>
	<h2> Vous panier est vide. <a href="<?= HOST; ?>"> Voir nos produits. </a> </h2>
<?php endif; ?>
