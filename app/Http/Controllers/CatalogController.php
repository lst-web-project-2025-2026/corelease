<?php

namespace App\Http\Controllers;

use App\Models\Catalog;

class CatalogController extends Controller
{
    public function index()

    {
       
        
        $Catalog = Catalog::allresources();




        return view('Catalog', ['Catalog' => $Catalog]);
    }
}
