<?php use_helper('dates');?>
<?php $title = "Mon historique"; ?>
<div class="dateSelect">

	<a href="<?= HOST; ?>utilisateur/historique/year/<?= $params->year-1; ?>/" style="border: 0px solid white"><?= $params->year-1; ?></a>
	<span>Vos commandes <?= $params->year; ?></span>
	<a href="<?= HOST; ?>utilisateur/historique/year/<?= $params->year+1; ?>/" style="border: 0px solid white"><?= $params->year+1; ?></a> 
</div>

<div class="wrapper active" style="width:95%; margin:auto; padding-bottom: 0px;">


<?php if($params->invoices):?>

    <?php foreach ($params->invoices as $invoice): ?>
		<a href="https://appli-v.net/download/i/v/<?= encodeInt($invoice->invoiceId);?>/i/c/" target="_blank">

			<div class="item active margin" style="height:auto; width: 95%; margin:auto; margin-bottom: 5rem;">

					<p style="font-size: 18px; color:white;">
						<strong> <?= showDate($invoice->date); ?> - <?= $invoice->priceTtc; ?>€ - 
						N°<?= $invoice->number; ?>
						</strong>
					</p>

					<?php foreach($invoice->invoiceProducts as $invoiceProductData):?>			
						<?php $description = null;?>
						<?php $invoiceProduct = $invoiceProductData['product'];?>
						<?php $quantity       = $invoiceProductData['quantity'];?>
						
						<?php if(isset($invoiceProductData['description'])):?>
							<?php foreach($invoiceProductData['description'] as $childname => $dates):?>
								<?php $alldates = implode('-', $dates);?>
								<?php $descriptionArr[]= $childname.': '.$alldates;?>
							<?php endforeach;?>
							<?php $description = implode(' | ', $descriptionArr);?>
							<?php unset($descriptionArr);?>
						<?php endif;?>

						
						<div class="invoiceProduct">
							<div>
								<b><?= $invoiceProduct->nameFr;?></b><br/>
								<div style="font-size:16px; width: 80%; text-overflow: ellipsis; overflow: hidden; margin: auto">
									<?= $description;?>
								</div>
								<?php if(isset($invoiceProductData['description2'])):?>
									<div>
										<?= $invoiceProductData['description2'];?>
									</div>
								<?php endif;?>
							</div>
						</div>


						<hr/>

					
					<?php endforeach;?>
		
			</div>
		</a>

  


    <?php endforeach; ?>
<?php else :?>


	Aucune inscription sur cette année

<?php endif?>

</div>
