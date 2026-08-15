<?php $title = "Connexion / Inscription"; ?>
<section class="section-our-engagements">

    <div class="schedule-row">
        <!-- Exemple d'une liste avec des pictos, se transforme en swiper sur petit écran -->
        <div class="m-swiper js-m-swiper swiper-signup swiper-container">
            <div>
                <form action="<?= API; ?>user/api/create" method="POST" id="signUpForm" novalidate="novalidate">
                    <article class="picto-list-item swiper-slide">
                        <h3 class="picto-list-title main-content-title"><span>première inscription</span></h3>
                        <p class="form-item">
                            <label for="firstEmail"><strong>Votre</strong> adresse e-mail</label>
                            <input id="firstEmail" name="username_signup" class="form-input-text" required type="email" title="Format incorrect" aria-required="true">
                        </p>
                        <p class="form-item">
                            <label for="firstPassword"><strong>Votre </strong> mot de passe</label>
                            <input id="firstPassword" name="plainPassword_signup" type="password" class="form-input-text" required title="Entrez le mot de passe" aria-required="true">
                            <br>
                        <div id="showPasswordButtonSignup">
                            <i class="material-icons">remove_red_eye</i>
                            <span id="showPasswordButtonSignupText">Afficher le mot de passe</span>
                        </div>
                        </p>
                        <p class="form-item">
                            <label for="firstFirstname"><strong>Votre </strong> prénom</label>
                            <input id="firstFirstname" name="firstname" type="text" class="form-input-text" required title="Entrez le mot de passe" aria-required="true">
                            <br>
                        </p>
                        <p class="form-item">
                            <label for="firstLastName"><strong>Votre </strong> nom</label>
                            <input id="firstLastName" name="lastname" type="text" class="form-input-text" required title="Entrez le mot de passe" aria-required="true">
                            <br>
                        </p>

                    </article>

                    <p>
                        <input type="submit" id="signUpButton" class="btn-primary" value="créer votre compte">
                        <br><br><br><br>
                    </p>
                </form>
            </div><!-- /list-picto -->
        </div>

        <div class="m-swiper js-m-swiper swiper-container" id="signIn">
            <div class="">
                <form action="<?= API; ?>user/api/authenticate" method="POST" id="signInForm">

                    <article class="picto-list-item swiper-slide">
                        <h3 class="picto-list-title main-content-title"><span>on se connait déjà !</span></h3>
                        <p class="form-item">
                            <label for="emailCustomer"><strong>Votre</strong> adresse e-mail</label>
                            <input id="emailCustomer" name="username" type="email" class="form-input-text" title="Entrez un email">
                            <br>
                        </p>
                        <p class="form-item">
                            <label for="passwordCustomer"><strong>Votre </strong> mot de passe</label>
                            <input id="passwordCustomer" name="password" type="password" class="form-input-text" title="Entrez un mot de passe">
                            <br />
                        <div id="showPasswordButtonLogin">
                            <i class="material-icons">remove_red_eye</i>
                            <span id="showPasswordButtonLoginText">Afficher le mot de passe</span>
                        </div>
                        </p>
                    </article>
                    <p>
                        <input type="submit" id="loginButton" class="btn-primary" value="Me connecter">
                    </p>
                    <p>
                        <a href="<?= HOST ?>auth/lost-password">Mot de passe oublié ? </a>
                    </p>

                </form>
            </div><!-- /list-picto -->
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha1/0.6.0/sha1.js" type="text/javascript"></script>