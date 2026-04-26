<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Catalogue;
use App\Models\Partenaire;
use App\Models\Avis;

class HomeController extends Controller
{
    public function index()
    {
        $articles = Article::where('publie', true)
                    ->orderBy('date_publication', 'desc')
                    ->get();

        $catalogues = Catalogue::orderBy('ordre')->get();
        $partenaires = Partenaire::all();
        $avis = Avis::where('publie', true)->get();

        return view('welcome', compact('articles', 'catalogues', 'partenaires', 'avis'));
    }
}