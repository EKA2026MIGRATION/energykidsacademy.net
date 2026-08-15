<?php $i = 0; $title = "Tableau de bord"; use_helper('dates')?>

    <h2 class="main-content-title">
        <span> Bienvenue M/Mme <?= $_SESSION['PERSON_CONNECTED']->lastname;?></span>
    </h2>


    <?php if($params->personalProduct == 1 || count($params->registrationWaitings) > 0):?>
        <?php $i = 0;?>
        <div id="messageProduct" class="zoom-dark" style="background-color: darkred; color: white;"></div>
        <div class="wrapper active" style="width:95%; margin:auto; padding-bottom: 0px;">
            <?php foreach($params->productPersos as $childData => $productPerso):?>

                <?php $el = explode('|', $childData); $childname = $el[0]; $childid = $el[1];?>

                <?php if(count($productPerso) > 0):?>

                    <?php foreach($productPerso as $product):?>

                        <?php $i++;?>

                        <div style="cursor: pointer" class="addToCartProduct" data-productid="<?= $product->productId;?>" data-childid="<?= $childid;?>" data-personid="<?= $_SESSION['PERSON_CONNECTED']->personId;?>">

                                <div class="item active margin" style="padding: 0px; display: flex; height: auto; justify-content: space-between;; width: 95%; margin:auto; margin-bottom: 5rem;">

                                    <div style="text-align: center;  min-width: 180px; ">
                                        <b style="font-variant: small-caps slashed-zero; font-weight: bold; font-size: 24px"><?= $childname;?></b>
                                        <br/>
                                        Prix: <?= $product->priceTtc;?> €
                                    </div>

                                    <div class="details" style="margin-right: 20px">
                                        <ul style="font-size: 16px; text-align: left; list-style-type: none">
                                            <li><?= strip_tags($product->nameFr);?></li>
                                            <li><?= $product->descriptionFr;?></li>
                                        </ul>
                                    </div>

                                </div>
                        </div>
                    <?php endforeach;?>


                <?php endif;?>
            <?php endforeach;?>

            <?php foreach($params->registrationWaitings as $registrations):?>


                <?php if($registrations):?>

                    <?php foreach($registrations as $registration):?>

                        <?php $i++;?>


                        <?php $product = $registration->product; $child = $registration->child;?>

                        <div style="cursor: pointer" id="cartItemRegisration<?= $registration->registrationId;;?>" class="addToCartProductWaiting" data-registrationid = "<?= $registration->registrationId;?>" data-productid="<?= $product->productId;?>" data-childid="<?= $child->childId;?>" data-personid="<?= $_SESSION['PERSON_CONNECTED']->personId;?>">

                            <div class="item active margin" style="padding: 0px; display: flex; flex-wrap: wrap; height: auto; justify-content: space-between;; width: 95%; margin:auto; margin-bottom: 5rem;">

                                <div style="text-align: center; min-width: 180px; ">
                                    <b style="font-variant: small-caps slashed-zero; font-weight: bold; font-size: 24px"><?= $registration->child->fullname;?></b>
                                    <br/>
                                    Prix: <?= $product->priceTtc;?> €
                                </div>

                                <div class="details" style="margin-right: 20px">
                                    <ul style="font-size: 16px; text-align: left; list-style-type: none;">
                                        <li><?= trim(strip_tags($product->nameFr));?></li>
                                        <?php foreach($registration->sessions as $info):?>
                                            <?php if($info->date) echo '<li>Date : '.showDate($info->date).'</li>';?>
                                        <?php endforeach;?>
                                    </ul>
                                </div>

                            </div>
                        </div>

                    <?php endforeach;?>
                <?php endif;?>

            <?php endforeach;?>



        </div>
    <?php endif;?>



    <?php if($_SESSION['canRegister'] == 0): ?>
        <div style="color:red; font-weight:bold;"> Attention, vous devez compléter votre compte avant de procéder à une inscription.</div>
        Vous devez :<br/>
        <ul>
            <?php if($_SESSION['nbAddresses'] == 0):?> 
                <li>
                    <a href="<?= HOST ?>utilisateur/profil/d/<?= encodeInt(PERSON_CONNECTED['personId']); ?>/">
                        Ajouter <b>une adresse</b> pour permettre la prise en charge
                    <a>
                </li>
            <?php endif;?>
            <?php if($_SESSION['nbPhones'] == 0):?> 
                <li>
                    <a href="<?= HOST ?>utilisateur/profil/d/<?= encodeInt(PERSON_CONNECTED['personId']); ?>/">
                        Ajouter <b>un numéro de téléphone</b> pour que nous puissions vous contacter
                    </a>
                </li>
            <?php endif;?>
            <?php if($_SESSION['nbChildren'] == 0):?> 
                <li>
                    <a href="<?= HOST ?>utilisateur/ajouter-un-enfant">
                        Ajouter au moins <b>un enfant</b>
                    </a>
                </li>
            <?php endif;?>
        </ul>
        <br/>
        Une fois effectuée ce message disparaitra et vous pourrez procéder aux inscriptions. 
    <?php else: ?>

        <p style="text-align: justify; text-align: center">
            Vous pouvez inscrire votre enfant à toutes les activités sportives organisées par Energy Kids Academy :<br>
        </p>


        <div class="ea-product-row">

                        <div class="ea-product">
                    <a href="<?= HOST ?>ea/category/id/<?= encodeInt(10);?>,<?= encodeInt(11);?>/" class="picto-list-link">
                        <div class="picto-list-illust jungle">
                            <img src="<?= IMG ?>ecoledesport.svg" alt="école de sport" height="107" width="130">
                        </div>
                        <div style="text-align: center;">
                            <h3 class="picto-list-title">Cours à l'année<br><small>club avec transport</small></h3>
                        </div>
                        <!--
                        <p class="picto-list-text">
                            Inscriptions trimestrielles pour les Mercredi, Samedi et Dimanche de l&#039;année. En tennis, foot et golf.
                        </p>-->
                    </a>
                </div>
                        <div class="ea-product">
                    <a href="<?= HOST ?>ea/a-la-carte/id/<?= encodeInt(5);?>/" class="picto-list-link">
                        <div class="picto-list-illust jungle">
                            <img src="<?= IMG ?>jungle.svg" alt="école de sport" height="107" width="130">
                        </div>
                        <div style="text-align: center;">
                            <h3 class="picto-list-title">Stage à la séance<br><small>vacances scolaires</small></h3>
                        </div>
                        <!--
                        <p class="picto-list-text">
                            Inscription à la demi-journée ou journée, tous les jours de la semaine. Un service sur mesure.
                        </p>-->
                    </a>
                </div>
                        <div class="ea-product">
                    <a href="<?= HOST ?>ea/category/id/<?= encodeInt(4);?>/" class="picto-list-link">
                        <div class="picto-list-illust jungle">
                            <img src="<?= IMG ?>coupe.svg" alt="école de sport" height="107" width="130">
                        </div>
                        <div style="text-align: center;">
                            <h3 class="picto-list-title">Compétition<br><small>vacances scolaires</small></h3>
                        </div>
                        <!--
                        <p class="picto-list-text">
                            Inscription pour des stages de compétiton de tennis aux tournois FFT.
                        </p>-->
                    </a>
                </div>
                        <div class="ea-product">
                    <a href="<?= HOST ?>ea/category/id/<?= encodeInt(6);?>/" class="picto-list-link">
                        <div class="picto-list-illust jungle">
                            <img src="<?= IMG ?>drapeau_anglais.svg" alt="école de sport" height="107" width="130">
                        </div>
                        <div style="text-align: center;">
                            <h3 class="picto-list-title">Sport &amp; Anglais<br><small>vacances scolaires</small></h3>
                        </div>
                        <!--
                        <p class="picto-list-text">
                            Inscription à la semaine pour un stage de sport et d&#039;anglais encadré par Berlitz.
                        </p>-->
                    </a>
                </div>
                        <div class="ea-product">
                    <a href="<?= HOST ?>ea/category/id/<?= encodeInt(7);?>/" class="picto-list-link">
                        <div class="picto-list-illust jungle">
                            <img src="<?= IMG ?>about-gymnases.svg" alt="école de sport" height="107" width="130">
                        </div>
                        <div style="text-align: center;">
                            <h3 class="picto-list-title">Cours à l'année<br><small>gymnases parisiens</small></h3>
                        </div>
                        <!--
                        <p class="picto-list-text">
                            1h de sport tous les Samedi de l&#039;année. Dans un gymnase dans Paris, près de chez vous.
                        </p>-->
                    </a>
                </div>
                        <div class="ea-product">
                    <a href="<?= HOST ?>ea/category/id/<?= encodeInt(3);?>/" class="picto-list-link">
                        <div class="picto-list-illust jungle">
                            <img src="<?= IMG ?>candle.jpg" alt="école de sport" height="107" width="130">
                        </div>
                        <div style="text-align: center;">
                            <h3 class="picto-list-title">Anniversaire<br><small>club avec transport</small></h3>
                        </div>
                        <!--
                        <p class="picto-list-text">
                            Inscription pour les goûters d&#039;anniversaires tous les après-midis.
                        </p>-->
                    </a>
                </div>
                        <div class="ea-product">
                    <a href="<?= HOST ?>ea/category/id/<?= encodeInt(12);?>/" class="picto-list-link">
                        <div class="picto-list-illust jungle">
                            <img src="<?= IMG ?>fille_toiles_yeux.svg" alt="école de sport" height="107" width="130">
                        </div>
                        <div style="text-align: center;">
                            <h3 class="picto-list-title">Sorties à thèmes<br><small>avec transport</small></h3>
                        </div>
                        <!--
                        <p class="picto-list-text">
                            Sorties aux Parcs d&#039;attractions et aux compétitions sportives internationales.
                        </p>-->
                    </a>
                </div>
                        <div class="ea-product">
                    <a href="<?= HOST ?>ea/category/id/<?= encodeInt(2);?>/" class="picto-list-link">
                        <div class="picto-list-illust jungle">
                            <img src="<?= IMG ?>sapin.svg" alt="école de sport" height="107" width="130">
                        </div>
                        <div style="text-align: center;">
                            <h3 class="picto-list-title">Alpe d'Huez<br><small>séjours vacances</small></h3>
                        </div>
                        <!--
                        <p class="picto-list-text">
                            Transport TGV - Installation en appartement: des vacances à la montagne en 1er classe.
                        </p>-->
                    </a>
                </div>


        
            
        </div>


        <div class="zoom-dark" style="text-align: left">

            <div style="width: 10%; float: left; font-size: 30px">
                <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
            </div>
            <div style="width: 90%; float: left">
                Nous vous rappelons qu’une inscription n’est enregistrée et validée qu’accompagnée de son règlement par carte bancaire.<br>
            </div>

            <br style="clear: both">
            <br>

            <div style="width: 10%; float: left; font-size: 36px">
                <i class="fa fa-envelope-o" aria-hidden="true"></i>
            </div>
            <div style="width: 90%; float: left">
                L’inscription réglée, une confirmation de transaction et un récapitulatif d’inscription sont envoyés par mail.<br>
            </div>
            <br style="clear: both">


        </div>

    <?php endif; ?>

    <p style="text-align: justify">
        <br>
        Nous restons à votre disposition 7j/7 du lundi au dimanche pour vous accompagner dans votre inscription au téléphone ou par mail. N’hésitez pas à nous contacter si vous avez des questions ou si vous avez besoin d’aide !.<br>
        <br>
        <!--N’hésitez pas à nous contacter si vous avez des questions ou si vous avez besoin d’aide !<br/>-->

        </p><ul class="inline-list inline-list-two-col contact-us">
            <li>
                Par téléphone :<br><b>01 47 01 59 60</b>
                <!--Par téléphone : <br><strong>01 47 01 59 60</strong>--->
            </li>
            <li>
                Par mail : <br><a href="mailto:contact@energykidsacademy.net"><strong>contact@energykidsacademy.net</strong></a>
            </li>
        </ul>
  <p></p>


  <script>
        let nbProductString = "<?php echo $i;?>";
        let nbProduct = parseInt(nbProductString);
        let message;

        if(nbProduct > 1) {
            message = "Vous avez plusieurs produits en attente de paiement";
        } else {
            message = "Vous avez 1 produit en attente de paiement";
        }

        message += "<br/><br/><b>CLIQUEZ SUR LE PRODUIT POUR L'AJOUTER AU PANIER</b>";

        document.getElementById('messageProduct').innerHTML = message;


        $('.addToCartProductWaiting').click(function(e) {
            e.preventDefault();

            let child = $(this).data('childid');
            let product = $(this).data('productid');
            let person = $(this).data('personid');
            let registrationid = $(this).data('registrationid');


            let data = { registrationid : registrationid, child: child, product : product, person: person, payed: 0, status: "cart", sessions : [], sports : []};
            let preferences = [];

            let url = "registration/create";
            let type = "POST";

            $.ajax({
                type: "POST",
                url: urlRequest,
                data: {type, url, data, preferences},
                dataType: "json",
                beforeSend() {

                },
                success(json) {
                    countCart();
                    viewCart();
                    toastr.success('Ajouté au panier');

                    $("#cartItemRegisration" + registrationid)
                        .addClass("animated bounceOutUp")
                        .delay(750)
                        .hide(0);




                },
                error(json)
                {
                    console.log(json);
                }
            });


            return false;
        })

  </script>

