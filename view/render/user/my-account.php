<?php $title = "Mes accès"; ?>
<!--
<div style="color:darkred; font-weight:bold;">
    Bonjour,<br/>
    Le temps du mot de passe unique est révolu.
    Vous disposez désormais de votre compte personnel.<br/>
    <br/>
    Nous invitons à entrer un nouveau mot de passe personnel pour pouvoir accéder à votre compte.<br/>
    Vous pourrez ainsi procéder à vos inscriptions.<br/>
    <br/><br/>
</div>
-->

<form action="<?= API;?>user/api/change-user-password/<?= PERSON_CONNECTED['identifier']; ?>" method="PUT" id="changePassWord" style="max-width: 400px; margin:auto;" novalidate="novalidate">

        <h3 class="picto-list-title main-content-title"><span>Changer le mot de passe du compte</span></h3>
        <p class="form-item">
            <label for="newPassWord"><strong>Nouveau</strong> mot de passe</label>
            <input id="newPassWord" name="new_password" class="form-input-text" required="" type="password" >
            <br/>
            <div id="showPasswordButton" style="display: flex; cursor: pointer; color: darkblue; text-align: left; font-style: italic;">
                <i class="material-icons">remove_red_eye</i>
                &nbsp;&nbsp;&nbsp;
                <span>Afficher le mot de passe</span>
            </div>
        </p> 

    <p>
        <input type="submit" style="display: block; width:100%" class="btn-primary" value="Changer mon mot de passe">
        <br><br><br><br>
    </p>
</form>

<!--
<form action="<?= API;?>user/api/modify/<?= PERSON_CONNECTED['identifier']; ?>" method="PUT" id="changeEmail" style="max-width: 400px; margin:auto;" novalidate="novalidate">

        <h3 class="picto-list-title main-content-title"><span>Changer l'email</span></h3>
        <p class="form-item">
            <label for="newEmail"><strong>Nouvelle</strong> adresse email</label>
            <input id="newEmail" name="new_email" class="form-input-text" required="" type="email" >
        </p> 

    <p>
        <input type="submit" style="display: block; width:100%" class="btn-primary" value="Changer mon email">
        <br><br><br><br>
    </p>
</form>
--->



<script>

    let showPasswordButton = document.getElementById('showPasswordButton');
    let newPassWord = document.getElementById('newPassWord');
    showPasswordButton.addEventListener('click', function() {
        if (newPassWord.type === "password") { 
            newPassWord.type = "text"; 
        } 
        else
        { 
            newPassWord.type = "password"; 
        } 
    })

</script>