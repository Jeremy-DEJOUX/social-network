<?php 

use App\Autloader;
use App\Models\AnnoncesModel;

require_once 'Autoloader.php';
Autloader::register();

$model = new AnnoncesModel;

$donnees = [
    'titre' => 'Annonce hydratée',
    'description' => 'Description de l\'annonce hydratée',
    'actif' => 0
];

$annonces = $model->hydrate($donnees);

$model->create($annonces);

var_dump($annonces);
