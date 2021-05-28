<?php 
use App\Autloader;
use App\Core\Main;

//On définier une constante contenant le dossier racine du projet
define('ROOT', dirname(__DIR__));

//On importe l'autoloader
require_once ROOT.'/Autoloader.php';
Autloader::register();


//On instancie Main (notre routeur)
$app = new Main;

//On démarre l'application
$app->start();