<?php $title = "Mot de passe oublié ?"; ?>

<form action="<?= API;?>user/api/reset-password-confirm/<?= $params->token; ?>" method="PUT" id="lostPassWordFormConfirm" style="max-width: 400px; margin:auto;" novalidate="novalidate">
		<input type="hidden" id="token" value="<?= $params->token; ?>">
        <h3 class="picto-list-title main-content-title"><span>Changer le mot de passe</span></h3>
        <p class="form-item">
            <label for="newPassWord"><strong>Nouveau</strong> mot de passe</label>
            <input id="newPassWord" name="new_password" class="form-input-text" required="" type="password" >
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


<div id="showLogButton" style="display: none; width: 60%; margin: auto">
    <a href="<?= HOST;?>" style="display: block; width:100%" class="btn-primary">SE CONNECTER</a>
</div>

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