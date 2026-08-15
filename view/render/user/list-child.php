<?php $title = "Mes enfants"; ?>

<a href="<?= HOST ?>utilisateur/ajouter-un-enfant" class="btn-primary" style="display: block; margin-top: 20px;">Ajouter un enfant</a></center>

<div class="wrapper active" style="width:95%; margin:auto; padding-bottom: 0px;">
    <?php foreach (PERSON_CONNECTED['children'] as $child): ?>

        <a href="<?= HOST ?>enfant/profil/i/<?= encodeInt($child['childId']); ?>/">
    
            <div class="item active margin child<?= $child['childId']; ?>" style="height:auto; width: 95%; margin:auto; margin-bottom: 5rem;">

            <img src="<?= ($child['photo'] != "") ? URL_PHOTO.$child['photo'] : IMG.'no_photo.jpg';  ?>" style="max-width: 50px; max-height: 50px;">

            <p style="font-size: 18px; color:white;"><strong> <?= $child['firstname']; ?>  <?= $child['lastname']; ?> </strong></p>

            <div class="icon" style="display: block;"><i class="material-icons">edit</i></div>

            </div>
        </a>


    <?php endforeach; ?>


</div>


