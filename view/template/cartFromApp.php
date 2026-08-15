<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>


    <?php if(isset(FILES_ROUTE[ROUTE])) { foreach(FILES_ROUTE[ROUTE]['css'] as $item){
    if($item != "") {?>
      <link rel="stylesheet" href="<?= CSS;?><?php echo $item; ?>">
    <?php } } } ?>
    <link rel="stylesheet" href="<?= CSS;?>toast.min.css">
    <link rel="stylesheet" href="<?= CSS;?>styles.css">
    <link rel="stylesheet" href="<?= CSS;?>app.css">
    <link rel="stylesheet" href="<?= CSS;?>animate.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="<?= IMG; ?>favicon.png">


  </head>
  <body>


          <?= $contentPage ?>


    <input type="hidden" id="urlApi" value="<?= API ?>">
    <input type="hidden" id="urlPhoto" value="<?= URL_PHOTO ?>savePhoto">
    <input type="hidden" id="urlRequest" value="<?= HOST ?>sendRequest">
    <input type="hidden" id="urlHost" value="<?= HOST ?>">
    <input type="hidden" id="mapsApiKey" value="<?= MAPS_API_KEY ?>">
    <input type="hidden" id="photoProfilDefault" value="<?= IMG ?>no_photo.jpg">
    <input type="hidden" id="noPhoto" value="<?= IMG ?>no_photo_2.jpg">
    <input type="hidden" id="token" value="<?= TOKEN; ?>">

    <script src="<?= JS;?>vendor/jquery.js"></script>
    <script src="<?= JS;?>vendor/toast.min.js"></script>
    <script src="<?= JS ?>global.min.js"></script>
    <script src="<?= JS;?>serializeToJson.js"></script>
    <script src="<?= JS;?>app.js"></script>

    <?php
    // AUTLOAD FILES JS -> REDUCTION TEMPS CHARGEMENTS
    if(isset(FILES_ROUTE[ROUTE])) { foreach(FILES_ROUTE[ROUTE]['js'] as $item){
    if($item != "" && $item != "google-maps") {?>
      <script src="<?= JS;?><?php echo $item; ?>"></script>
    <?php }
    elseif($item == "google-maps")
    {
    ?>
      <script src="https://maps.googleapis.com/maps/api/js?key=<?= MAPS_API_KEY ?>&libraries=places"></script>
    <?php
    } } }
    ?>


  </body>
</html>
