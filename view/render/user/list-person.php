<?php $title = "Profils associés"; ?>
<input type="hidden" name="connectedId" id="connectedId" value="<?= PERSON_CONNECTED['personId']; ?>"/>

<div class="wrapper active" style="width:95%; margin:auto; padding-bottom: 0px;">

	<!--
	<a href="<?= HOST ?>utilisateur/profil/id/<?= PERSON_CONNECTED['personId']; ?>/">

		<div class="item active margin person<?= PERSON_CONNECTED['personId'] ?>" style="height:auto; width: 95%; margin:auto; margin-bottom: 5rem;">
		<img src="<?= (PERSON_CONNECTED['photo'] != "") ? HOST.PERSON_CONNECTED['photo'] : IMG.'no_photo.jpg';  ?>" style="max-width: 50px; max-height: 50px;">
		<p style="font-size: 18px; color:white;"><strong> <?= PERSON_CONNECTED['firstname']; ?>  <?= PERSON_CONNECTED['lastname']; ?> </strong></p>
		<div class="icon" style="display: block;"><i class="material-icons">edit</i></div>
		</div>
	</a>
	-->

	<?php foreach (PERSON_CONNECTED['related'] as $person): ?>

		<div class="item active margin personAssociatedDiv" id="personIdDiv-<?= $person['personId'];?>" style="cursor: default; height:auto; width: 95%; margin:auto; margin-bottom: 5rem;" >
	
		<i class="material-icons personDeAssociatedDiv" id="person-<?= $person['personId']; ?>" style="cursor: pointer; font-size: 3rem; color: red; float: right; margin-right: 20px">close</i>

	
	<!--
			<img src="<?= ($person['photo'] != "") ? URL_PHOTO.$person['photo'] : IMG.'no_photo.jpg';  ?>" style="max-width: 50px; max-height: 50px;">
	-->
			<p style="font-size: 18px; color:white; cursor: default"><strong> <?= $person['firstname']; ?>  <?= $person['lastname']; ?> </strong></p>

	<!--
			<div class="icon" style="display: block;"><i class="material-icons">edit</i></div>
	-->

			<div id="disclaimer-<?= $person['personId']; ;?>" style="display: none; background-color: white; padding: 20px; border: 3px solid darkred; border-radius: 10px; color: darkblue; max-width: 500px; margin: auto">
				Vous allez supprimer l'association avec cette personne. <br/>
				Cette annulation est irréversible. Voulez-vous continuer ?<br/>
				<br/>
					<button class="btn-primary goOnButton" id="goOnButton-<?= $person['personId'];;?>">
						Continuer
					</button>
					<button class="btn-primary cancelledButton" id="cancelledButton-<?= $person['personId'];;?>">
						Annuler
					</button>

			</div>

		</div>



	<?php endforeach; ?>


</div>

<h3 class="site-main-title">Pourquoi associer un profil ?</h3>
	<p style="font-size: 1.8rem; text-align: justify-content; color: darkblue">
		Vous pouvez associer une nouvelle personne à votre profil: un.e parent.e, un.e ami.e par exemple.<br/>
		En associant cette personne, vous lui donnez accès aux profils de vos enfants. Cette personne pourra inscrire vos enfants.<br/>
		Cependant elle ne verra jamais les inscriptions que vous aurez effectuées depuis votre profil. Vous ne verrez jamais les inscriptions effectués par ces personnes.<br/>
		<br/>
		Si vous avez seulement besoin d'ajouter une adresse de prise en charge, vous n'avez pas besoin de créer un profil associé.<br/>
		Vous pouvez l'ajouter directement sur votre profil.
		<br/><br/>
	</p>

<a href="<?= HOST ?>utilisateur/ajouter-un-profil" class="btn-primary" style="display: block; margin-top: 20px;">
	Ajouter un profil
</a>





