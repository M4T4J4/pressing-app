<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
   

    public function index()
    {
        // Récupère les 100 dernières activités
        $activities = Activity::latest()->paginate(25);
        return view('activity.index', compact('activities'));
    }
}