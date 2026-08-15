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

	<div class="wrapper active" style="width:95%; margin:auto; padding-bottom: 0px; display: flex; flex-wrap: wrap; justify-content: center">

		<?php foreach ($params->cart as $cart): ?>

		  		<div id="cartItemRegisration<?= $cart->registrationId;?>" class="item active margin cartItem<?= $cart->registrationId; ?>" style="height:350px; width: 200px; margin: 20px; margin-bottom: 5rem;">
				  		<div class="close" onclick="deleteCartItem('<?= $cart->registrationId; ?>')" style="display: block; top: 25px; right: 10px"></div>

						<p style="font-size: 18px; color:white;"><strong> <?= $cart->product->nameFr; ?> </strong></p>
						<div style="height: 70px; overflow: auto">
							<?php foreach ($cart->sessions as $date): ?>
									<?= date('d/m/y', strtotime($date->date)); ?> &nbsp;&nbsp;
							<?php endforeach; ?>
						</div>
					    <?= $cart->child->firstname; ?> <br />
					    <?= $cart->location->name; ?> <br />
					    <br/>
					    Activités :
					    <?php if ($cart->sports != ''): foreach ($cart->sports as $sport): ?>
					    		 <?= $sport->name; ?>&nbsp;&nbsp;
					    <?php endforeach; endif; ?>

					    <br/>
					    <?php if ($cart->product->transport == true): ?>
					    		Transport compris
					    <?php endif; ?>

					    <br/>

					    <p style="font-size: 22px; font-weight:bold; color:white;"><center><?= $cart->product->priceTtc; ?> €</center></p>

						<br/>
						<?php $created = showDate($cart->createdAt); ?>
						<?php if($created != $today->format('d/m/Y')):?>
							<div style="background-color: darkred; color: white">Produit ajouté le <?= showDate($cart->createdAt); ?></div>
						<?php endif;?>

			  	</div>


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
		<div class="btn-primary with-icon" id="confirmButton">ACCEDEZ AU PAIEMENT - <?= $params->priceTotal; ?> €</div>
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
