<?php

namespace App\Core;

use App\Controllers\MainController;

/**
 * Routeur Principale
 */
class Main
{
    public function start()
    {
        //On retire le "trailling slash" éventuel de l'UTR
        //On récupère l'URL
        $uri = $_SERVER['REQUEST_URI'];
        
        //On vérifie que $uri n'est pas vide et se termine pas un slash
        if (!empty($uri) && $uri[-1] === "/" && $uri != '/') {
            //on enlève le /
            $uri = substr($uri, 0, -1);

            //On envoie un code de redirection permanente 
            http_response_code(301);

            //On redirige vers l'url sans le slah
            header('location: '.$uri);
        }

        //On gère les paramètres de l'URL
        //p=Controller/Méthode/paramètres
        //on sépare les paramètres dans un tableau
        $params = explode('/', $_GET['p']);

        if ($params[0] != '') {
            //On à au moins 1 paramètres
            //On récupère le nom du controller à instancié
            //On met une majuscule en première Lettre, on ajoute le namespace compet avant et on ajoute controller aprés
            $controller = '\\App\\Controllers\\' . ucfirst(array_shift($params)) . 'Controller';

            //On instancie le controller
            $controller = new $controller;
            
            //On récupère le deuxième paramères d'URL
            $action = (isset($params[0])) ? array_shift($params) : 'index';

            if (method_exists($controller, $action)){
                //Si il reste des paramètres on les passe à la méthode
                (isset($params[0])) ? $controller->$action($params) : $controller->$action();
                
            }else{
                http_response_code(404);
                echo "La page recherché n'existe pas";
            }

        }else{
            //On n'a pas de paramètres, on instancie le controller par défault
            $controller = new MainController;
            
            //On apelle la méthode index
            $controller->index();
        }
    }
}