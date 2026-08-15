<?php $title = "Stage à la carte"; ?>

<style>
	.dateAvailableDatePicker a { color: darkblue!important; background-color: limegreen!important}
	.dateClosedDatePicker span { color: white!important; background-color: darkred!important}
	.dateAlmostClosedDatePicker a { color: white!important; background-color: orange!important}
	.square { width: 25px; height: 25px; border-radius: 2px; margin-right: 4px}
	#showFullLegend, #showAlmostLegend { display: flex; width: 400px}
</style>


<div style="display: none;">
	<h2 class="main-content-title">
		<span>Téléphone pour les notifications SMS</span><br />
		<i style="font-size: 12px; font-style: italic">Sélectionner le numéro à utuliser en priorité pour l'envoi de SMS</i>
	</h2>

	<div class="wrapper active radio" id="listPhones">
		<?php foreach (PERSON_CONNECTED['phones'] as $phone) : ?>

			<div class="item" data-id-phone="<?= $phone['phoneId']; ?>" onclick="changePhone(this)" style="height: 100px">

				<?= $phone['phone']; ?>
				<br />
				<b style="font-variant-caps: small-caps;"><?= $phone['name']; ?></b>

				<div class="close"></div>
			</div>

		<?php endforeach; ?>
	</div>
</div>

<h2 class="main-content-title">
	<span>Adresse de départ du transport</span>
</h2>

<div class="wrapper active radio" id="listAddresses">

	<?php foreach (PERSON_CONNECTED['addresses'] as $address) : ?>

		<div class="item" data-id-address="<?= $address['addressId']; ?>" data-id-postal="<?= $address['postal']; ?>" onclick="changeAddress(this)">

			<?= $address['address']; ?>

			<div class="close"></div>
		</div>

	<?php endforeach; ?>


</div>


<center>
	<div id="datepicker"></div>
</center>
<br/>
<center id="showLegend">
		<br/>
		<b>Disponibilités sur les jours à venir</b><br/><br/>
		<div id="showFullLegend">
			<div class="square" style="background-color: lightgreen;">&nbsp;</div>
			Inscription ouverte
		</div>
		<br/>
		<div id="showAlmostLegend">
			<div class="square" style="background-color: orange;">&nbsp;</div>
			Quelques places restantes
		</div>
		<br/>
		<div id="showFullLegend">
			<div class="square" style="background-color: darkred; opacity: 0.5">&nbsp;</div>
			Journée Complète
		</div>
</center>

<!--

<center><h2> Montant :  <span class="amountTotal">0</span> euros </h2></center>

<div class="form-submit btn-primary add-cart">
	<input type="button" class="btn-primary with-icon" onclick="addToCart()" value="Ajouter au panier">
</div>

<h2 class="main-content-title">
	        <span>Date(s) sélectionnée(s)</span>
	</h2>-->

<div class="wrapper margin active" id="listDate" style="display: flex; flex-wrap: wrap; justify-content: space-around; width: 100%">
</div>




<label for="modal-alacarte" id="modal-alacarte-open"></label>
<input class="modal-state" id="modal-alacarte" type="checkbox" />
<div class="modal">
	<label class="modal__bg" for="modal-alacarte"></label>
	<div class="modal__inner">
		<label class="modal__close" for="modal-alacarte"></label>
		<h2>Choix des options</h2>

		<h2 class="main-content-title">
			<span>Choix du produit</span>
		</h2>


		<div class="wrapper active radio">
			<div class="item active margin" style="height:auto;">

				<aside class="choiceProduct">

					<?php foreach ($params->product as $product) : ?>
						<input type="radio" id="product<?= $product->productId; ?>" name="choiceProduct" onclick="loadProduct(this)" data-product-name="<?= $product->nameFr; ?>" data-id-product="<?= $product->productId; ?>">
						<label for="product<?= $product->productId; ?>"><?= $product->nameFr; ?></label><br />
					<?php endforeach ?>
					<input type="hidden" id="productName" value="" />

				</aside>

			</div>
		</div>

		<div id="contentProduct"></div>


		</form>

	</div>
</div>

<input type="hidden" id="sessionDate" value="">
<input type="hidden" id="phone" value="<?= PERSON_CONNECTED['phones'][0]['phoneId']; ?>">
<input type="hidden" id="address" value="">
<input type="hidden" id="postal" value="">
<input type="hidden" id="categoryId" value="<?= decodeInt(trim($params->categoryId)); ?>">