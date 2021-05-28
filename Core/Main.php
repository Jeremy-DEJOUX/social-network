<?php

namespace App\Core;

class Main
{
    public function start()
    {
        //On retire le "trailling slash" éventuel de l'UTR
        //On récupère l'URL
        $uri = $_SERVER['REQUEST_URI'];
        
        //On vérifie que $uri n'est pas vide et se termine pas un slash
        if (!empty($uri) && $uri[-1] === "/") {
            //on enlève le /
            $uri = substr($uri, 0, -1);

            //On envoie un code de redirection permanente 
            http_response_code(301);

            //On redirige vers l'url sans le slah
            header('location: '.$uri);
        }
    }
}