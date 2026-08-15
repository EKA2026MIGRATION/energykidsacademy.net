<?php $title = $params->firstname." ".$params->lastname; ?>
<?php $h1 = $title.' <a href="'.HOST.'utilisateur/ajouter-un-enfant/i/'.encodeInt($params->childId).'/"><i class="material-icons">mode_edit</i></a>';?>




  <ul class="slide">
    <li id="deleteChild" data-id-child="<?= $params->childId ?>"><a href="#">Supprimer cet enfant</a></li>
  </ul>



<div class="page__profil" style="margin-bottom: 2rem;">

    <div class="profile__picture">

        <img src="<?= ($params->photo != "") ? URL_PHOTO.$params->photo : IMG.'no_photo.jpg';  ?>" />

    </div>

</div>
   <div class="card-wrap horizontal">
                <div class="card-img-container">
                    <figure>
                        <i class="material-icons">date_range</i>
                    </figure>
                </div>
                <div class="card-info">
                    <div class="card-primary">
                        <figure>
                            <p class="card-title">Date de naissance</p>
                            <p><?= date('d/m/Y', strtotime($params->birthdate)); ?></p>
                        </figure>
                    </div>
                </div>
            </div>

            <div class="card-wrap horizontal">
                <div class="card-img-container">
                    <figure>
                        <i class="material-icons">phone</i>
                    </figure>
                </div>
                <div class="card-info">
                    <div class="card-primary">
                        <figure>
                            <p class="card-title">Téléphone</p>
                            <p><?= (null !==$params->phone)? $params->phone: '-' ?></p>
                        </figure>
                    </div>
                </div>
            </div>

            <div class="card-wrap horizontal hight">
                <div class="card-img-container">
                    <figure>
                        <i class="material-icons">local_hospital</i>
                    </figure>
                </div>
                <div class="card-info">
                    <div class="card-primary">
                        <figure>
                            <p class="card-title">Informations médicales</p>
                            <p><?= $params->medical ?></p>
                        </figure>
                    </div>
                </div>
            </div>


            <div class="card-wrap horizontal hight">
                <div class="card-img-container">
                    <figure>
                        <i class="material-icons">directions_car</i>
                    </figure>
                </div>
                <div class="card-info">
                    <div class="card-primary">
                        <figure>
                            <p class="card-title">Préférence pour le transport</p>
                            <p><?= $params->pickupInstruction ?></p>
                        </figure>
                    </div>
                </div>
            </div>

            <div class="card-wrap horizontal hight">
                <div class="card-img-container">
                    <figure>
                        <i class="material-icons">location_on</i>
                    </figure>
                </div>
                <div class="card-info">
                    <div class="card-primary">
                        <figure>
                            <p class="card-title">Résident français</p>
                            <p><?php if($params->franceResident == 1) { echo 'Oui'; } else { echo 'Non'; } ?></p>                        
                        </figure>
                    </div>
                </div>
            </div>




            <?php if(isset($params->school->schoolId)): ?>
                <div class="card-wrap horizontal hight" style="padding: 10px">
                <!--
                    <div class="card-img-container">
                        <figure>
                            <img style="top:10px; left:15px; max-height: 100px; max-width: 100px;" src="<?= ($params->school->photo != "") ? $params->school->photo : IMG.'no_photo.jpg';  ?>" />
                        </figure>
                    </div>-->
                    <div class="card-info">
                        <div class="card-primary">
                            <figure>
                                <p class="card-title">École : <?= $params->school->name; ?></p>
                                <p><?= $params->school->address ?> <?= $params->school->town ?>, <?= $params->school->postal ?>, <?= $params->school->country ?></p>
                            </figure>
                        </div>
                    </div>
                </div>
                <br/><br/><br/><br/><br/>
            <?php endif; ?>


<h2  style="margin-top: 2rem;"> Profils associés </h2>
<div class="flex space-arround">
    <?php foreach($params->persons as $person):?>
        <div class="card">

          <div class="card-banner">
            <div class="card-profile" style="background-image: url('<?= ($person->photo != "") ? URL_PHOTO.$person->photo : IMG.'no_photo.jpg';  ?>');">
            </div>
            <h3><?= $person->firstname.' '.$person->lastname; ?> </h3>
            <?php if($person->personId == PERSON_CONNECTED['personId'] ):?>
                <aside>
                    <a href="<?= HOST ?>utilisateur/profil/id/<?= $person->personId; ?>/">Afficher le profil</a>
                </aside>
            <?php endif;?>
          </div>
        </div>
    <?php endforeach; ?>
</div>
