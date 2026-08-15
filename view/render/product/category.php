<?php $title = 'Choisir un produit'; ?>

<style>
    #titleCategoryBar { display: flex; justify-content: space-around; flex-wrap: wrap}
    .titleCategory { border: 2px solid darkblue; padding: 6px; cursor: pointer}
</style>

<div id="titleCategoryBar">
    <?php $i = 0;?> 
    <?php foreach($params->categorys as $publicName => $elements):?>
        <?php $i++;?>
        <div class="titleCategory" data-categorykey= "<?= $i;?>">
            <?= $publicName;?>
        </div>
    <?php endforeach;?>
</div>
<br/><br/>


<?php $j = 0; foreach($params->categorys as $publicName => $elements):?>
    <?php $j++;?>

    <h2 id="category<?= $j?>" style="font-family: Pacifico, sans-serif; text-align: center;color: #182d61;"><?= $publicName ;?></h2>

    <div class="wrapper active" style="width:95%; margin:auto; padding-bottom: 0px;">
        <?php if(isset($elements['products']) && count($elements['products']) > 0):?>
                    <?php foreach ($elements['products'] as $product): ?>

                        <?php if($product->visibility != "full"):?>
                            <a href="<?= HOST ?>ea/selection/produit/<?= $product->productId; ?>/" id="product-<?= $product->productId;?>">
                        <?php endif;?>

                            <?php ($product->visibility == "full") ? $backstyle = "background-color: darkred; cursor: default;" : $backstyle ="" ;?>
                    
                            <div class="item active margin child<?= $product->productId; ?>" style="<?= $backstyle;?> height:auto; width: 95%; margin:auto; margin-bottom: 3rem;">

                                <?php if($product->photo != "" ):?>
                                    <img src="<?= URL_PHOTO.$product->photo;?>" style="max-width: 50px; max-height: 50px;">
                                <?php endif;?>

                                <?= $product->nameFr; ?>

                                <p style="color:white; <?php if($product->visibility == "full") echo 'cursor: default;';?>"><?= $product->descriptionFr; ?> </p>

                                <?php if($product->visibility == "full"):?>
                                    <p style="background-color: darkblue; padding: 10px; color: white;">
                                        Ce produit est victime de son succès, il est complet.
                                    </p>
                                <?php endif;?>


                            </div>
                        <?php if($product->visibility != "full"):?>
                            </a>
                        <?php endif;?>
                    <?php endforeach; ?>
        <?php else :?>
                <div style="text-align: center">
                        Aucun produit n'est actuellement disponible dans cette catégorie.
                </div>
        <?php endif;?>


    </div>
    <br/>


<?php endforeach;?>

<input type="hidden" id="pidInput" value="<?= $params->pid;?>"/>