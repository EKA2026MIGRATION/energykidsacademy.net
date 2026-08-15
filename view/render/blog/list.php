<?php $title = "Le Blog"; ?>
<style>
     p { font-size: 1.6rem; color: darkgrey; max-width: inherit!important; text-align: justify}
    .articles { margin-bottom: 40px; max-height: 300px; 
                display: flex;
                overflow: hidden !important;
                justify-content: space-around;
                flex-wrap: wrap}
    .articles .articleDetail { width: 40%; max-width: 350px!important; min-width: 300px!important; max-height: 300px!important}
    .articleDetail p { min-width: 300px!important }

    a:hover { border-bottom-width: 0px!important;}

    .blockTextArticle { border-radius: 6px}

    .blockTextArticle:hover { background-color: #EDEDED}
    

</style>


    <?php foreach ($params as $blog): ?>
    <div class="blockTextArticle">
        <br/>&nbsp;
        <a class="articles" href="<?= HOST ?>blog/article/id/<?= $blog->blogId; ?>/">
    
            <div class="articleDetail">
                <?php if(strpos($blog->photo, 'http') !== false):?>
                    <img src="<?= $blog->photo;?>" alt="illustration <?= $blog->title;?>" style="object-fit: cover; height: 100%"/>
                <?php else :?>
                    <img src="<?= ($blog->photo != "") ? URL_PHOTO.$blog->photo : IMG.'no_photo_2.jpg';  ?>" style="object-fit: cover"/>
                <?php endif;?>
            </div>

            <div class="articleDetail articleText">
                <h3><?= $blog->title;;?></h3>
                <p>
                    <?= mb_strimwidth($blog->content, 0, 450, '...'); ?> 
                </p>
            </div>
        </a>
        <a class="articles" href="<?= HOST ?>blog/article/id/<?= $blog->blogId; ?>/" style="font-weight: bold; font-style: italic">
            Lire la suite
            <br/>&nbsp;
        </a>
    </div>
        <hr/>
        <br/>


    <?php endforeach; ?>
