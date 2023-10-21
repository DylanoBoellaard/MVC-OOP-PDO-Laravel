<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instructeur;         // Import instructeur model
use Illuminate\Support\Facades\DB;

class InstructeurController extends Controller
{
    public function index()
    {
        //$instructeurListaaaa = DB::select('select * from instructeurs');

        $instructeurList = DB::table('instructeurs')
            ->select('voornaam', 'tussenvoegsel', 'achternaam', 'mobiel', 'datumInDienst', 'aantalSterren')
            ->orderBy('aantalSterren', 'desc')
            ->get();

        return view('instructeur.index', ['instructeurList' => $instructeurList]);
    }

    public function gebruikteVoertuigen()
    {
        //kekw
    }
}
