<?php $title = "Accès Espace Personnel"; ?>
<div class="wrapper active" style="width:85%; margin:auto; padding-top: 0px; text-align: justify; margin-top: -50px">
    <div style="font-weight: bold; text-align: center">Le mot de passe <span style="color: darkred">ZLATAN</span> a vécu.</div>
    <br />
    Désormais vous avez un espace personnel dans lequel nous ajouterons progressivement un contenu sur mesure sur votre activité au club<br />
    <br />
    Votre nouvelle inscription nécessite d'utiliser un <b>email unique</b> ainsi qu'un <b>mot de passe unique</b>.
    <br /><br />
    En cliquant sur le bouton ci-dessous, vous recevrez un email avec les instructions vous permettant de créer votre mot de passe personnel.
</div>


<form action="<?= API; ?>user/api/reset-password" method="PUT" id="lostPassWordForm" style="max-width: 400px; margin:auto;" novalidate="novalidate">

    <h3 class="picto-list-title main-content-title"><span>Créer votre mot de passe personnel </span></h3>
    <p class="form-item">
        <label for="firstEmail"><strong>Votre</strong> adresse e-mail</label>
        <input id="firstEmail" value="<?= $params->email; ?>" name="email" class="form-input-text" required="" type="email" title="Format incorrect" aria-required="true">
    </p>

    <p>
        <input type="submit" style="display: block; width:100%" class="btn-primary" value="Envoyer le lien de création">
        <br><br>
    </p>

    <div class="wrapper active" style="margin:auto; padding-top: 0px; text-align: justify;">
        PS: Une fois la demande de ré-initialisation de mot de passe demandée, si n'avez pas reçu d'email, pensez à vérifier dans vos spams.<br />
        Dans le cas où n'auriez toujours rien reçu, appelez le standard au 01 47 01 59 60.
    </div>
</form>