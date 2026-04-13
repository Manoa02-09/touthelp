<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Models\Formation;
use App\Models\Expertise;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCatalogues = Catalogue::count();
        $totalFormations = Formation::count();
        $totalExpertises = Expertise::count();

        return view('admin.dashboard', compact('totalCatalogues', 'totalFormations', 'totalExpertises'));
    }
}