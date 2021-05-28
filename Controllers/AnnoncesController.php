<?php

namespace App\Controllers;

class AnnoncesController extends Controller
{
    public function index()
    {
        $donnes =['a', 'b'];
        include_once ROOT.'/Views/annonces/index.php';
    }
}