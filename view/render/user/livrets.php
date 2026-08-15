<?php $title = "Livrets"; ?>

<div class="wrapper active" style="width:95%; margin:auto; padding: 0;">
    <?php foreach ($params->child as $child) : ?>
        <div class="item margin child<?= $child['childId']; ?>" style="background-color: #003593; height:auto; width: 95%; margin:auto; margin-bottom: 0rem;">

            <img src="<?= ($child['photo'] != "") ? URL_PHOTO . $child['photo'] : IMG . 'no_photo.jpg';  ?>" style="max-width: 50px; max-height: 50px;">

            <p style="font-size: 18px; color:white;"><strong>
                    
                    <?php if (is_array($child['booklets'])) : ?>
                        <?php if (count($child['booklets']) > 1) : ?>
                            Livrets
                        <?php else : ?>
                            Livret
                        <?php endif; ?>
                    <?php else : ?>
                        Livret
                    <?php endif; ?>

                    <?= $child['firstname']; ?> <?= $child['lastname']; ?> </strong></p>
        </div>

        <?php if (is_array($child['booklets'])) : ?>
            <?php foreach ($child['booklets'] as $booklet) : ?>
                <a target="_blank" href="https://appli-v.net/livret/energy/enfant/<?= encodeInt($booklet->bookletChildArray->id); ?>/<?= sha1($child['firstname'].$child['lastname']); ?>">
                    <div class="bookletItem">

                        <div><?= $booklet->bookletChildArray->booklet->name; ?> - <?= date('d/m/Y', strtotime($booklet->bookletChildArray->dateEvaluation)); ?> </div>
                        <div><i class="material-icons">visibility</i></div>

                    </div>
                </a>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="bookletItemEmpty">
                Aucun livret disponible pour <?= $child['firstname']; ?> <?= $child['lastname']; ?>
            </div>
        <?php endif; ?>
        <div style="height: 40px;"></div>
    <?php endforeach; ?>
</div>

