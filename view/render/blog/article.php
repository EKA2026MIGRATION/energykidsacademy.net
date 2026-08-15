<?php $title = $params->title; ?>
<style>
     p { font-size: 1.6rem; color: black; text-align: justify}
    #article { }
	#article img { float: left; max-width: 300px; margin-right: 30px; margin-bottom: 20px }
</style>

<small><a href="<?= HOST; ?>blog/list">Revenir aux articles</a></small>


<div id="article">
		<?php if(strpos($params->photo, 'http') !== false):?>
			<img src="<?= $params->photo;?>" alt="illustration <?= $params->title;?>" style="object-fit: cover; height: 100%"/>
		<?php else :?>
			<img src="<?= ($params->photo != "") ? URL_PHOTO.$params->photo : IMG.'no_photo_2.jpg';  ?>" style="object-fit: cover"/>
		<?php endif;?>
		<h3><?= $params->title;;?></h3>
		<p><?= $params->content; ?> </p>
</div>

<small>Publié le <?= date('d/m/Y', strtotime($params->createdAt)); ?> par <?= $params->author; ?></small>
