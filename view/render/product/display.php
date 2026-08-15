<?php $title = "Choix des options"; ?>
<?php use_helper('age'); $showAllSports = null?>

<center>
	<h3><?= $params->nameFr; ?></h3>
</center>

<div style="height:30px;"></div>

<?php if (ROUTE != "ea/loadProductALacarte") : ?>

	<div style="display: none;">
		<h2 class="main-content-title">
			<span>Téléphone pour les notifications SMS</span>
		</h2>

		<div class="wrapper active radio" id="listPhones">

			<?php foreach (PERSON_CONNECTED['phones'] as $phone) : ?>

				<div class="item" data-id-phone="<?= $phone['phoneId']; ?>" onclick="changePhone(this)">

					<?= $phone['phone']; ?>

					<div class="close"></div>
				</div>

			<?php endforeach; ?>


		</div>
	</div>

	<?php if ($params->transport == true) : ?>

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
	<?php else:?>
		<?php $i=0; foreach (PERSON_CONNECTED['addresses'] as $address) : ?>
			<?php $i++;?>
			<?php if($i > 1) continue;?>
			<input type="hidden" id="address" value="<?= $address['addressId']; ?>">
		<?php endforeach; ?>


	<?php endif; ?>
<?php endif; ?>

<h2 class="main-content-title">
	<span>Choisissez le/les enfant(s) qui participeront</span>
</h2>
<div class="wrapper" id="listChild">
	<?php foreach (PERSON_CONNECTED['children'] as $child) : ?>

        <?php $currentAge = showAge($child['birthdate'], false); ?>

        <?php ($currentAge > 5) ? $showAllSports = 1 : $showAllSports = 0; ?>

		<div class="item" data-child-id="<?= $child['childId']; ?>" data-child-age="<?= $currentAge; ?>"  onclick="changeChild(this)">
			<?= $child['firstname']; ?>

			<div class="close" onclick="changeChild(this)"></div>
		</div>
	<?php endforeach; ?>


</div>



<?php if (ROUTE != "ea/loadProductALacarte") : ?>
	<?php if ($params->isDateSelectable == true) : ?>

		<h2 class="main-content-title">
			<span>Choisir la date</span>
		</h2>

		<center>
			<div id="datePicker" <?php echo (empty($params->dates)) ? '' : 'style="display:none;"';  ?>></div>
		</center>

		<div class="wrapper active radio" id="listDates">

			<?php foreach ($params->dates as $date) : ?>

				<div class="item" onclick="changeDate(this)" data-date="<?= $date; ?>">

					<?= date('d/m/Y', strtotime($date)); ?>

					<div class="close"></div>
				</div>

			<?php endforeach; ?>


		</div>
	<?php else : ?>

		<div class="wrapper active" style="width: 90%">
			<h2 class="main-content-title">
				<span>Date(s)</span>
			</h2>
			<div style="display: flex; flex-wrap: wrap; justify-content: center; overflow: auto; height: 200px ">
				<?php foreach ($params->dates as $date) : ?>
					<div class="item active margin" style="width: 100px; margin: 0px 10px 10px 10px">

						<?= date('d/m/y', strtotime($date)); ?>

					</div>
				<?php endforeach; ?>
			</div>
		</div>



	<?php endif; ?>
<?php endif; ?>

<?php if ($params->isHourSelectable == true) : ?>

	<h2 class="main-content-title">
		<span>Choisir la plage horaire</span>
	</h2>
	<div class="wrapper radio">

		<?php foreach ($params->hours as $hour) : ?>


			<div class="item" data-end-hour="<?= $hour->end; ?>" data-start-hour="<?= $hour->start; ?>" <?php if ($hour->is_full == 1) : ?> style="background-color: #e02e2e; color: white;" data-full="1" <?php else : ?> onclick="changeHour(this)" data-full="0" <?php endif; ?>>
				<?= $hour->start; ?> - <?= $hour->end; ?>

				<?php if ($hour->is_full == 1) :
					echo ' - ' . $hour->message_fr;
				endif; ?>

				<div class="close"></div>
			</div>
		<?php endforeach; ?>


	</div>

<?php else : ?>

	<!--
			<div class="wrapper active">
				<h2 class="main-content-title">
				        <span>Horaire(s)</span>
				</h2>		
				<?php foreach ($params->hours as $hour) : ?>
		  		<div
		  			class="item active margin"
		  			<?php if ($hour->is_full == 1) : ?>
		  				style="background-color: #e02e2e;"
		  			<?php endif; ?>
		  		>
			    	<?= $hour->start; ?> - <?= $hour->end; ?>

	  				<?php if ($hour->is_full == 1) :
							echo ' - ' . $hour->message_fr;
						endif; ?>
		  				

			  	</div>
			  	<?php endforeach; ?>
			</div>
-->


<?php endif; ?>




<?php if ($params->isLocationSelectable == true) : ?>

	<h2 class="main-content-title">
		<span>Choisir le lieu</span>
	</h2>
	<div class="wrapper radio">

		<?php foreach ($params->locations as $location) : ?>

			<div class="item" data-location="<?= $location->locationId; ?>" onclick="changeLocation(this)">
				<?= $location->name; ?>

				<div class="close"></div>
			</div>
		<?php endforeach; ?>


	</div>


<?php else : ?>


	<div class="wrapper active">
		<h2 class="main-content-title">
			<span>Lieu</span>
		</h2>
		<div class="item active margin">

			<?= $params->locations[0]->name; ?>

		</div>

	</div>

<?php endif; ?>




<?php if ($params->isSportSelectable == true) : ?>

	<h2 class="main-content-title">
		<span>Choisir le/les activités</span>
	</h2>
	<div class="wrapper" id="listSports">

        <?php $class = ""; $messageSport = ""; ?>
        <?php foreach ($params->sports as $sport) : ?>
            <?php
            if ($sport->sportId == 9) {
                $messagesSports = "Le multisport permet aux enfants de moins de 6 ans de découvrir aussi bien le tennis, le foot et le golf";
                $class = "multisport";
            } else {
                $class = "allsports";
                $messagesSport = "Les enfants de plus de 6 ans peuvent choisir entre le tennis, le foot et le golf";
            }
            ?>
            <div class="item <?= $class; ?> sportSelected" data-sport="<?= $sport->sportId; ?>" data-sport-name="<?= $sport->name; ?>" onclick="changeSport(this)">
                <?= $sport->name; ?>
                <div class="close" onclick="changeSport(this)"></div>
            </div>
        <?php endforeach; ?>

        <ul>
            <li style="font-size: 12px; font-style: italic" class="<?= $class;?>"> <?= $messageSport; ?></li>
        </ul>

	</div>


<?php else : ?>


	<div class="wrapper active">
		<h2 class="main-content-title">
			<span>Activités</span>
		</h2>

		<?php foreach ($params->sports as $sport) : ?>

			<div class="item active margin">
				<?= $sport->name; ?>

			</div>
		<?php endforeach; ?>


	</div>

<?php endif; ?>



<center>
	<h2> Montant : <span class="amount"><?php echo $params->priceTtc; ?></span> euros </h2>
</center>

<div class="form-submit btn-primary">
	<input type="button" class="btn-primary with-icon" onclick="addToCart()" value="Ajouter au panier">
</div>


<?php
$listSport = "";
if ($params->isSportSelectable == false) :
	foreach ($params->sports as $sport) :
		$listSport = $listSport . $sport->sportId . ',';
	endforeach;
	$listSport = substr($listSport, 0, -1);
endif;

$listDate = "";
if ($params->isDateSelectable == false) :
	foreach ($params->dates as $date) :
		$listDate = $listDate . $date . ',';
	endforeach;
	$listDate = substr($listDate, 0, -1);
endif;


?>

<input type="hidden" id="personId" value="<?= PERSON_CONNECTED['personId']; ?>">

<input type="hidden" id="locationId" value="<?php echo ($params->isLocationSelectable == false) ? $params->locations[0]->locationId : '';  ?>">
<input type="hidden" id="sportId" value="<?= $listSport ?>">

<input type="hidden" id="sessionStart" value="<?php echo ($params->isHourSelectable == false) ? $params->hours[0]->start : '';  ?>">
<input type="hidden" id="sessionEnd" value="<?php echo ($params->isHourSelectable == false) ? $params->hours[0]->end : '';  ?>">

<input type="hidden" id="child">
<input type="hidden" id="pricebase" value="<?php echo $params->priceTtc; ?>">
<input type="hidden" id="price" value="<?php echo $params->priceTtc; ?>">


<input type="hidden" id="product" value="<?php echo $params->productId; ?>">
<input type="hidden" id="dropIn" value="<?php echo $params->hourDropin; ?>">
<input type="hidden" id="dropOff" value="<?php echo $params->hourDropoff; ?>">
<input type="hidden" id="isTransport" value="<?php echo ($params->transport == true) ? '1' : '0';  ?>">
<?php if (ROUTE != "ea/loadProductALacarte") : ?>
	<input type="hidden" id="sessionDate" value="<?php echo ($params->isDateSelectable == false) ? $listDate : '';  ?>">
	<input type="hidden" id="phone" value="<?= PERSON_CONNECTED['phones'][0]['phoneId']; ?>">

	
	<input type="hidden" id="address" value="">
	<input type="hidden" id="postal" value="">
<?php else : ?>
	<script type="text/javascript">
		$('.item').click(function() {

			if ($(this).hasClass('active')) {

			} else {
				if ($(this).parent().hasClass('radio')) {

					var nb = $(this).parent().children('.active').length;
					if (nb != 0) {
						$(this).parent().children('.active').removeClass('active').removeClass('margin').children('.close').fadeOut();
					}

					$(this).parent().addClass('active');
					$(this).children('.close').fadeOut(300);
					$(this).addClass('active').addClass('margin');
					$('.close', this).delay(700).fadeIn(300);

				} else {

					$(this).parent().addClass('active');
					$(this).children('.close').fadeOut(300);
					$(this).addClass('active').addClass('margin');
					$('.close', this).delay(700).fadeIn(300);
				}

			}

		});

		$('.close').click(function(event) {
			event.stopPropagation();

			var nb = $(this).parent().parent().children('.active').length;

			if (nb == 0) {
				$(this).parent().parent().removeClass('active');
			}


			$(this).parent().removeClass('active').removeClass('margin');
			$(this).fadeOut(300);
		});




		var changeHour = (data) => {
			$("#sessionEnd").val($(data).attr('data-end-hour') + ':00');
			$("#sessionStart").val($(data).attr('data-start-hour') + ':00');
		}

		var changeLocation = (data) => {
			$("#locationId").val($(data).attr('data-location'));
		}



		var changeChild = (data) => {
			let priceBase = $("#pricebase").val();
			var i = 0;
			setTimeout(function() {


				var listChild = '';

				$("#listChild")
					.find(".item.active")
					.each(function() {
						i++;

						if (listChild != '') {
							listChild = listChild + ',' + $(this).attr('data-child-id');
						} else {

							listChild = $(this).attr('data-child-id');

						}




					});

				$("#child").val(listChild);


				if (i == 0 || i == 1) {
					$(".amount").html(priceBase);
				} else {
					let price = priceBase * i;

					$(".amount").html(price);
				}


			}, 1000);


		}


		var changeSport = (data) => {
			setTimeout(function() {
				var listSport = '';
				$("#listSports")
					.find(".item.active")
					.each(function() {

						if (listSport != '') {
							listSport = listSport + ',' + $(this).attr('data-sport');
						} else {
							listSport = $(this).attr('data-sport');
						}




					});

				$("#sportId").val(listSport);
			}, 1000);
		}
	</script>
<?php endif; ?>