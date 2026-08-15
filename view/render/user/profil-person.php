<?php $title = $params->firstname." ".$params->lastname; ?>
<?php $h1 = $title.' <a href="'.HOST.'utilisateur/ajouter-un-profil/d/'.encodeInt($params->personId).'/"><i class="material-icons">mode_edit</i></a>';?>

<!--<li id="deletePerson" data-id-person="<?= $params->personId ?>"><a href="#">Supprimer le profil</a></li>-->

<input class="modal-state" id="modal-phone" type="checkbox" />
<div class="modal">
  <label class="modal__bg" for="modal-phone"></label>
  <div class="modal__inner">
    <label class="modal__close" for="modal-phone"></label>
    <h2>Téléphone</h2>

      <div class="containerLoader" id="loaderFormEditPhone" style="display: none;"><div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>


      <form method="post" id="phoneForm" action="phone/create">
        <div class="form-item">
            <label>Nom du numéro *
              <input type="text" name="name" class="form-input-text" placeholder="Ex: Domicile, Bureau, Personnel, etc." required oninvalid="this.setCustomValidity('Seules des lettres sont acceptées')">
            </label>
        </div>
         
          <div class="form-item">
            <label>Numéro de téléphone*
              <input type="tel" name="phone" class="form-input-text" placeholder="Numéro de téléphone" required>
            </label>
          </div>
          <div class="form-item">
            <center><button type="submit" class="btn-primary" class="button">Envoyer </button></center>
          </div>

    </form>

  </div>
</div>           

<input class="modal-state" id="modal-address" type="checkbox" />
<div class="modal">
  <label class="modal__bg" for="modal-address"></label>
  <div class="modal__inner">
    <label class="modal__close" for="modal-address"></label>
    <h2>Adresse</h2>

      <div class="containerLoader" id="loaderFormEditAddress" style="display: none;"><div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>

         

      <form method="post" id="adresseForm" action="address/create">
          <div class="form-item">
            <input type="hidden" value="<?= $params->personId ?>" id="idPersonInput" />
            <label>Nom de l'adresse *
              <input type="text" name="name" class="form-input-text" placeholder="Ex: Principale, personnelle, domicile, etc." required>
            </label>
          </div>
          <div class="form-item">
            <label>Adresse *
              <input type="text" name="address"  class="form-input-text"  id="autocomplete" placeholder="Votre adresse" required>
            </label>
          </div>
          <div class="form-item">
            <label>Complément
              <input type="text" name="address2"  class="form-input-text" placeholder="Complément d'adresse" >
            </label>
          </div>
          <div class="form-item">
            <label>Code postal *
              <input type="number" name="postal" class="form-input-text" id="postal_code"  placeholder="Code postal" required>
            </label>
          </div>
          <div class="form-item">
            <label>Ville *
              <input type="text" name="town"  class="form-input-text" id="locality" placeholder="Ville" required>
            </label>
          </div>
          <div class="form-item">
            <label>Pays *
              <input type="text" name="country"  class="form-input-text" id="country" placeholder="Pays" required>
            </label>
          </div>
          <div class="form-item">
            <center><button type="submit" class="btn-primary">Envoyer </button></center>
          </div>
    </form>

  </div>
</div>


<div class="page__profil">

    <div class="profile__picture">

        <img src="<?= ($params->photo != "") ? URL_PHOTO.$params->photo : IMG.'no_photo.jpg';  ?>" />

    </div>

    <h2>Téléphones </h2>

    <?php foreach($params->phones as $phone):?>
      <div class="card-wrap horizontal" id="blockPhone<?= $phone->phoneId; ?>">
          
          <div class="card-img-container">
              <figure>
                  <i class="material-icons">phone</i>
              </figure>
          </div>
        
          <div class="card-info">
            
              <div class="card-primary">
                  <figure>

                          <p class="card-title"><?= $phone->name; ?></p>
                          <p><?= $phone->phone; ?> </p>
                  </figure>
              </div>
            
              <div class="card-secondary">
                  <label for="modal-phone" onclick="editPhone('<?= $phone->phoneId; ?>')"><span><i class="material-icons">mode_edit</i></span> Modifier</label>
                  <a href="javascript:void(0)" onclick="deletePhone('<?= $phone->phoneId; ?>')"><span><i class="material-icons">delete</i></span> Supprimer</a>
              </div>
            
          </div>
        
      </div>
    <?php endforeach; ?>
    
    <div class="card-wrap horizontal" style="max-width: 600px; margin: 0 auto">
          <div class="card-img-container">
              <figure>
                  <i class="material-icons" style="color: darkred">add_ic_call</i>
              </figure>
          </div>

          <div class="card-info">
            
            <div class="card-primary">
                <figure>
                    <p  onclick="changeActionPhone()" class="card-title" style="color: darkred"><label for="modal-phone">Ajouter un téléphone</label></p>
                </figure>
            </div>
          </div>
    </div>


    <h2>Adresses </h2>

    <?php foreach($params->addresses as $addresse):?>
      <div class="card-wrap horizontal" id="blockAdress<?= $addresse->addressId; ?>">
          
          <div class="card-img-container">
              <figure>
                <i class="material-icons">location_on</i>
              </figure>
          </div>
        
          <div class="card-info">
            
              <div class="card-primary">
                  <figure>

                      <p class="card-title"> <?= $addresse->name; ?></p>
                      <?= $addresse->address; ?> 

                      <?= $addresse->address2; ?> 

                      <br/> <?= $addresse->postal; ?> - <?= $addresse->town; ?>
                  </figure>
              </div>
            
              <div class="card-secondary">
                  <label for="modal-address" onclick="editAddress('<?= $addresse->addressId; ?>')"><span><i class="material-icons">mode_edit</i></span> Modifier</label>
                  <a href="javascript:void(0)" onclick="deleteAddress('<?= $addresse->addressId; ?>')"><span><i class="material-icons">delete</i></span> Supprimer</a>
              </div>
            
          </div>
        
      </div>
    <?php endforeach; ?>

    <div class="card-wrap horizontal" style="max-width: 600px; margin: 0 auto">
          <div class="card-img-container">
              <figure>
                  <i class="material-icons" style="color: darkred">pin_drop</i>
              </figure>
          </div>

          <div class="card-info">
            
            <div class="card-primary">
                <figure>
                  <p onclick="changeActionAdress()" class="card-title" style="color: darkred"><label for="modal-address">Ajouter une adresse</label></p>
                </figure>
            </div>
          </div>
    </div>

</div>


<h2> Enfants associés </h2>
<div class="flex space-arround">
    <?php foreach($params->children as $child):?>
        <div class="card">

          <div class="card-banner">
            <div class="card-profile" style="background-image: url('<?= ($child->photo != "") ? URL_PHOTO.$child->photo : IMG.'no_photo.jpg';  ?>');">
            </div>
            <h3><?= $child->firstname.' '.$child->lastname; ?> </h3>
            <aside>
            <a href="<?= HOST ?>enfant/profil/i/<?= encodeInt($child->childId); ?>/">Afficher le profil</a>
            </aside>
          </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=<?= MAPS_API_KEY ?>&libraries=places"></script>
