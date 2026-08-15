<?php
include_once('config/config.php');
isset($_GET['r']) ? $url = $_GET['r'] : $url = 'app/home';
MyConfiguration::start($url);
$routeur = new Routeur($url);
$routeur->renderController();
