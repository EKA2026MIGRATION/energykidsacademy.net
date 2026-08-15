<?php $title = "Paiement en attente"; use_helper('dates')?>


<?php foreach($params->registrationWaitings as $registrations):?>

    <?php foreach($registrations as $registration):?>

            <?php $product = $registration->product; $child = $registration->child;?>

            <div style="cursor: pointer" id="cartItemRegisration<?= $registration->registrationId;;?>" class="addToCartProduct" data-registrationid = "<?= $registration->registrationId;?>" data-productid="<?= $product->productId;?>" data-childid="<?= $child->childId;?>" data-personid="<?= $_SESSION['PERSON_CONNECTED']->personId;?>">

                <div class="item active margin" style="padding: 0px; display: flex; flex-wrap: wrap; height: auto; justify-content: space-between;; width: 95%; margin:auto; margin-bottom: 5rem;">

                    <div style="text-align: center; min-width: 180px; ">
                        <b style="font-variant: small-caps slashed-zero; font-weight: bold; font-size: 24px"><?= $registration->child->fullname;?></b>
                        <br/>
                        Prix: <?= $product->priceTtc;?> €
                    </div>

                    <div class="details">
                        <ul style="font-size: 16px; text-align: left; list-style-type: none">
                            <li><?= trim(strip_tags($product->nameFr));?></li>
                            <?php foreach($registration->sessions as $info):?>
                                <?php if($info->date) echo '<li>Date : '.showDate($info->date).'</li>';?>
                            <?php endforeach;?>
                        </ul>
                    </div>

                    <div class="btn-primary">
                        <input type="button" class="with-icon" class="addToCartProduct" data-registrationid = "<?= $registration->registrationId;?>" data-productid="<?= $product->productId;?>" data-childid="<?= $child->childId;?>" data-personid="<?= $_SESSION['PERSON_CONNECTED']->personId;?>" style="color: white; cursor: pointer; background-color: #d0112b; border: 0px solid red" value="Ajouter au panier">
                    </div>
                </div>
            </div>

    <?php endforeach;?>

<?php endforeach;?>

<script>



    $('.addToCartProduct').click(function(e) {
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

    });
</script>
