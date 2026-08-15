<?php $update = 0; if(isset($params->firstname)):  $update = 1; endif ?>
<?php if(1 === $update) { $title = "Modifier le profil"; } else { $title = "Ajouter un profil"; } ?>

<form action="person/<?= (1 === $update) ? 'modify/'.$params->personId : 'create';  ?>" method="post" id="personForm">
        <input type="hidden" name="photo" id="photoUrl" value="<?= (1 === $update) ?  $params->photo: '';  ?>">
        <input type="hidden" id="personId" value="<?= PERSON_CONNECTED['personId']; ?>">

        <div class="form-item">
            <label for="firstname2">
                Prénom
            </label>
            <input id="firstname" value="<?= (1 === $update) ?  $params->firstname: '';  ?>" name="firstname" class="form-input-text" type="text" maxlength="25" maxleng required>
        </div>

        <div class="form-item">
            <label for="last_name">
                Nom 
            </label>
            <input id="last_name" value="<?= (1 === $update) ?  $params->lastname: '';  ?>" name="lastname" class="form-input-text" type="text" maxlength="25" required>
 		    </div>
      
        <?php if($update == 0):  ?>
          <div class="form-item">
            <label for="last_name">
                Email 
            </label>
            <input id="email" name="email" class="form-input-text" type="email" maxlength="50" required>
 		    </div>
        <div class="form-item">
            <label for="last_name">
                Relation par rapport à vous
            </label>
            <input id="relationText" placeholder="Exemple : Parent, Famille, Conjoint" class="form-input-text" type="text" maxlength="25" required>
        </div>
        <?php endif ?>


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
      <div style="text-align: center; margin-top: 2rem;"> 
          <input type="submit" class="btn-primary " value="<?= (1 === $update) ?  'Modifier le profil': 'Ajouter le profil';  ?>">
      </div>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha1/0.6.0/sha1.js" type="text/javascript"></script>

