<?php $title = "Mot de passe oublié ?"; ?>

<?php if(!isset($_GET['email'])): ?>
<form action="<?= API;?>user/api/reset-password" method="PUT" id="lostPassWordForm" novalidate="novalidate">


    <h3 class="picto-list-title main-content-title"><span>Réinitialiser le mot de passe</span></h3>
    <p class="form-item">
        <label for="firstEmail"><strong>Votre</strong> adresse e-mail</label>
        <input id="firstEmail" name="email" class="form-input-text" required="" type="email" title="Format incorrect"
            aria-required="true">
    </p>

    <p class="resetPasswordButton">
        <input type="submit" id="lostPassWordButton" class="btn-primary" value="Réinitialiser mon mot de passe">
    </p>

</form>
<?php else: ?>


<div class="resetPasswordOptions">
    <h3>Comment souhaitez-vous réinitialiser votre mot de passe ? </h3>
    <p>Par email</p>
    <div class="resetPasswordOptions__email">
        <label>
            <input type="radio" class="email" name="choiceReset" value="<?= decrypt($_GET['email']); ?>" />
            <?= decrypt($_GET['email']); ?>
        </label>
    </div>

    <?php if(isset($_GET['phone'])):?>
    <p>Par sms</p>
    <div class="resetPasswordOptions__phone">
        <?php foreach($_GET['phone'] as $phone): ?>
        <label>
            <input type="radio" class="phone" name="choiceReset" value="<?= decrypt($phone); ?>" />
            <?= $maskedPhone = substr(decrypt($phone), 0, 4) . "******" . substr(decrypt($phone), 9, 1); ?>
        </label>
        <?php endforeach; ?>
    </div>
    <?php endif;?>
    <p class="resetPasswordButton">
        <input type="button" onclick="resetPassword()" id="lostPasswordReset" class="btn-primary"
            value="Réintialiser mon mot de passe">
    </p>
</div>

<div class="resetPasswordOptionsMessage" style="display: none;">

</div>

<a class="btn-primary resetPasswordOptionsButton" style="display: none;" href="<?= HOST; ?>">Me connecter</a>

<?php endif; ?>