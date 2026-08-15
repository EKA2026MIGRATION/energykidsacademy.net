
<?php $title = "Associer les enfants"; ?>
<?php $newProfil = $params->newProfil;?> 

<h2 class="main-content-title">
    <span>
        Cochez sur les enfants pour l'associer à <?= $newProfil->firstname.' '.$newProfil->lastname;?>
    </span>
</h2>

<div style="color:red; font-weight:bold; text-align: center">
    Attention, vous devez associer les SEULS enfants que <span style="color: darkblue"><?= $newProfil->firstname.' '.$newProfil->lastname;?></span> pourra voir.<br>
    Une fois cette étape passée, vous ne pourrez plus modifier cette association.
</div>


<form action="<?= HOST;?>utilisateur/doAssociation" method="POST">
    <div class="wrapper active" style="width:95%; margin:auto; padding-bottom: 0px;">
        <?php foreach (PERSON_CONNECTED['children'] as $child): ?>

                <input type="hidden" value="<?= $newProfil->personId;?>" name="personId"/>
        
                <div id="divChildId<?= $child['childId'];?>" class="item active margin childItem" data-childid="<?= $child['childId']; ?>"  style="height:auto; width: 95%; margin:auto; margin-bottom: 5rem; background-color: lightgrey">

                    <input type="hidden" id="inputChildId<?= $child['childId'];?>" value="0" name="childId[]"/>

                    <img src="<?= ($child['photo'] != "") ? URL_PHOTO.$child['photo'] : IMG.'no_photo.jpg';  ?>" style="max-width: 50px; max-height: 50px;">

                    <p style="font-size: 18px; color:white;"><strong> <?= $child['firstname']; ?>  <?= $child['lastname']; ?> </strong></p>

                </div>

        <?php endforeach; ?>
    </div>


    <input type="submit" class="btn-primary" value="VALIDER" style="width: 100%">

</form>

