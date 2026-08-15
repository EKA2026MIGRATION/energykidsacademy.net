<?php $update = 0; if(isset($params->firstname)):  $update = 1; endif ?>

<style type="text/css">
    .ui-datepicker
    {
        max-width: 320px;
    }

</style>

<?php if(1 === $update) { 



     $relations = array();
     foreach ($params->persons as $relation):

        $relations[$relation->personId] = $relation->relation;
    endforeach;

    $title = "Modifier le profil"; 


} else { $title = "Ajouter un enfant"; } ?>


<form action="child/<?= (1 === $update) ? 'modify/'.$params->childId : 'create';  ?>" method="post" id="childForm">
        <input type="hidden" id="personId" value="<?= PERSON_CONNECTED['personId']; ?>">
        <input type="hidden" name="photo" id="photoUrl" value="<?= (1 === $update) ?  $params->photo: '';  ?>">


        <div class="form-item">
            <label for="firstname2">
                Prénom <sup style="color: darkred">*</sup>
            </label>
            <input id="firstname" value="<?= (1 === $update) ?  $params->firstname: '';  ?>" name="firstname" class="form-input-text" type="text" title="3 caractères minimum" minlength="3" required>
        </div>

        <div class="form-item">
            <label for="last_name">
                Nom de l'enfant <sup style="color: darkred">*</sup>
            </label>
            <input id="last_name" value="<?= (1 === $update) ?  $params->lastname : $_SESSION['PERSON_CONNECTED']->lastname;  ?>" name="lastname" class="form-input-text" type="text" title="3 caractères minimum" minlength="3">
 		</div>

        <div class="form-item">
            <label for="birthday">Date de naissance <sup style="color: darkred">*</sup></label>
            <input id="birthday" value="<?= (1 === $update) ? date('d/m/Y', strtotime($params->birthdate)): '';  ?>" class="form-input-text" title="Format de date incorrect" type="text" required>
            <input type="hidden" id="datepicker" name="birthdate" value="<?= (1 === $update) ? date('Y-m-d', strtotime($params->birthdate)): '';  ?>">            
       </div>

 

        <div class="form-item">
            <label for="telephone">Téléphone <sup style="color: darkred"></label>
            <input id="telephone" name="phone" value="<?= (1 === $update) ?  $params->phone: '';  ?>" class="form-input-text" type="text">
        </div>


        <div class="form-item">
            <label for="school">École <sup style="color: darkred"></label>
            <?php if($update == 1 AND isset($params->school->schoolId)): ?>
                <input id="autocomplete" placeholder="Rechercher une école"  class="form-input-text" type="text" value="<?= (1 === $update) ?  $params->school->name: '';  ?>">
                <input type="hidden" id="school" name="school" value="<?= (1 === $update) ?  $params->school->schoolId: '';  ?>">
            <?php else: ?>
                <input id="autocomplete" placeholder="Rechercher une école"  class="form-input-text" type="text">
                <input type="hidden" id="school" name="school">
            <?php endif; ?>
        </div>

        <div class="form-item">
            <label for="gender">Sexe <sup style="color: darkred"></label>
              <select class="form-input-text" name="gender">
                <option value="h" <?php if(1 === $update): echo ($params->gender == "h") ? 'selected':''; endif ?>>Garçon</option>
                <option value="f" <?php if(1 === $update): echo ($params->gender == "f") ? 'selected':''; endif ?>>Fille</option>
              </select>
       </div>

       <div class="form-item">
            <label> Résident français<br/>
              <select name="france_resident" class="form-input-text">
                <option value="1" <?php if(1 === $update): echo ($params->franceResident == "1") ? 'selected':''; endif ?>>Oui</option>
                <option value="0" <?php if(1 === $update): echo ($params->franceResident == "0") ? 'selected':''; endif ?>>Non </option>
              </select>
            </label>
        </div>
        <div class="form-item">
            <label> Préférence de transport<br/>
              <select name="pickup_instruction" class="form-input-text">
                <option value="Le coach téléphone et j’accompagne mon enfant au minivan" <?php if(1 === $update): echo ($params->pickupInstruction == "Le coach téléphone et j’accompagne mon enfant au minivan") ? 'selected':''; endif ?>> Le coach téléphone et j’accompagne mon enfant au minivan </option>
                <option value="Le coach téléphone et mon enfant rentre seul du minivan" <?php if(1 === $update): echo ($params->pickupInstruction == "Le coach téléphone et mon enfant rentre seul du minivan") ? 'selected':''; endif ?>> Le coach téléphone et mon enfant rentre seul du minivan </option>
                <option value="Le coach ne téléphone pas et mon enfant rentre seul du minivan" <?php if(1 === $update): echo ($params->pickupInstruction == "Le coach ne téléphone pas et mon enfant rentre seul du minivan") ? 'selected':''; endif ?>> Le coach ne téléphone pas et mon enfant rentre seul du minivan</option>
              </select>
            </label>
       </div>


        <h3 class="main-content-title" style="margin-bottom: 20px;"><span>Renseignements complémentaires</span></h3>
         <div class="form-item">

              <?php if(is_object($params)) {

                  if(isset($params->medical)) {
                    $showMedical = 1;          
                  } else {
                    $showMedical = 0;
                  }


                } else {
                  $showMedical = 0;
                }?>
            
            <label><input type="checkbox" onclick="toogleMedical()" <?php if($showMedical == 1) echo 'checked';?>> J'ai une information médical à signaler
            </label>
        </div>

        <div class="form-item medical"  style="<?php echo ($showMedical == 1)  ? 'display: visible' : 'display: none' ?>">
            <label for="contact-medical-note">
                <i class="fa fa-medkit" aria-hidden="true"></i>
                &nbsp;  <span style="text-transform: uppercase">Informations médical <br/>
                    <small style="margin-left: 6px;">Merci ne de rien écrire si il n'y a rien à signaler. </small></span><br>
            </label>
            <i class="fa fa-medkit" aria-hidden="true" style="color: white;"></i>

    
            <textarea id="medical-note" name="medical" style="width: 100%;" ><?= (1 === $update) ?  $params->medical: '';  ?></textarea>
        </div>
        <div class="form-item flex space-between">
	
	        <div class="dropContainer flex-item c6" id="dropContainer">
	          <div class="contentDropContainer">

	            <div class="image-upload">

	              <label class="labelFileInput" for="fileInput">
	                <a class="button withIcon"> Parcourir mes fichiers </a>
	              </label>

	              <input type="file" id="fileInput" onchange="previewOnDiv()"/>

	            </div>
	            Glisser et déposer votre photo ici
	          </div>
	        </div>


	        <div class="photoContainer flex-item c6"><img src="<?php if(1 === $update): echo ("" != $params->photo) ? URL_PHOTO.$params->photo : IMG.'no_photo.jpg'; else: echo IMG.'no_photo.jpg'; endif ?>" id="photoRender"></div>
	     
	  	</div>


      <!--

        <h3 class="main-content-title"><span>Liaison avec les profils </span></h3>




        <p>Vous pouvez associer votre enfant à un profil avec la clé personnelle du votre profil</p>

                     
        <div class="wrapper active radio">
            <div class="item active margin" style="height:auto;">
          
                      <aside class="choiceProduct" >
                          <?php foreach (PERSON_CONNECTED['related'] as $person): ?>
                              <input type="checkbox" id="person<?= $person['personId']; ?>"
                              data-person="<?= $person['personId']; ?>" 
                              data-relation="<?php if($update == 1): if(array_key_exists($person['personId'], $relations)): echo $relations[$person['personId']]; endif; endif; ?>"  onclick="changeRelation(this)" <?php if($update == 1): if(array_key_exists($person['personId'], $relations)): echo 'checked'; endif; endif; ?>>
                              <label for="person<?= $person['personId']; ?>"><?= $person['firstname']; ?>  <?= $person['lastname']; ?><?php if($update == 1): if(array_key_exists($person['personId'], $relations)): echo ' - '.$relations[$person['personId']]; endif; endif; ?></label><br/>
                          <?php endforeach ?>
                      </aside>
            </div>
        </div>  -->      
        
        <div class="form-submit btn-primary">
            <input type="submit" class="btn-primary with-icon" value="<?= (1 === $update) ?  'Modifier le profil': 'Ajouter mon enfant';  ?>">
        </div>

    </form>

