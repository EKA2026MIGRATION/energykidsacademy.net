<?php $title = "Photos"; ?>
<?php use_helper('dates'); ?>
<style>
    .gallery {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: flex-start;
        max-width: 800px;
        margin: 0 auto;
    }

    .gallery-item {
        margin: 10px;
        cursor: pointer;
    }

    .gallery-item img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 5px;
    }

    .mymodal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .mymodal.show {
        opacity: 1;
        pointer-events: auto;
    }

    .mymodal-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
    }

    .mymodal-content img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        cursor: pointer;
    }
    .mymodal-description {
        margin-top: 10px;
        color: white;
        text-align: center;
    }
</style>


<div class="wrapper active" style="width:95%; margin:auto; padding: 0;">

    <div class="wrapper active" style="width:95%; margin:auto; padding: 0;">

        <?php $currentLabel = ""; $previousLabel = ""?>
        <div class="gallery">
            <?php foreach ($params->photos as $allphotos) : ?>
                <?php foreach ($allphotos as $photo) : ?>

                    <?php
                    $mediaDate = $photo->updatedAt;
                    $today =  date('Y-m-d');

                    $diffDate = newDiffDate($today, $mediaDate);

                    // switch diffDate
                    switch($diffDate) {
                        case 0:
                            $currentLabel = "Aujourd'hui";
                            break;
                        case 1:
                            $currentLabel = "Hier";
                            break;
                        case $diffDate > 1 && $diffDate <= 7:
                            $currentLabel = "La semaine dernière";
                            break;
                        case $diffDate > 7 && $diffDate <= 30:
                            $currentLabel = "Le mois dernier";
                            break;
                        case $diffDate > 30 && $diffDate <= 365:
                            $currentLabel = "L'année dernière";
                            break;
                        default:
                            $currentLabel = "Plus ancien";
                            break;
                    }

                    ?>

                    <?php if( $currentLabel !== $previousLabel) echo '<div style="width: 100%;"></div><b>'.$currentLabel.'</b><div style="width: 100%; color: red"></div>';?>

                    <div class="gallery-item">
                        <img src="https://appli-v.net/<?= $photo->url; ?>" alt="Photo de <?= $photo->child->full_name; ?>">
                        <div class="photo-details" style="display: none;">
                            <h4><?= $photo->title; ?></h4>
                            <?= $photo->description; ?>
                        </div>
                    </div>

                    <?php $previousLabel = $currentLabel; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>

        <div class="mymodal">
            <div class="mymodal-content">
                <button id="close-btn" style="cursor: pointer; float: right">X</button>
                <div class="mymodal-nav" style="font-size: 3rem; color: white; justify-content: center">
                    <span id="prev-btn" style="cursor: pointer">&lt;</span>
                    <span id="next-btn" style="cursor: pointer">&gt;</span>
                </div>
                <img id="mymodal-image" alt="">

                <div class="mymodal-description"></div>
            </div>
        </div>
    </div>
</div>
