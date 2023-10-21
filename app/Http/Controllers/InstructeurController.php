<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instructeur;         // Import models
use App\Models\Voertuig;
use App\Models\TypeVoertuig;
use App\Models\VoertuigInstructeur;
use Illuminate\Support\Facades\DB;

class InstructeurController extends Controller
{
    public function index()
    {
        $instructeurList = DB::table('instructeurs')
            ->select('id', 'voornaam', 'tussenvoegsel', 'achternaam', 'mobiel', 'datumInDienst', 'aantalSterren')
            ->orderBy('aantalSterren', 'desc')
            ->get();

        return view('instructeur.index', ['instructeurList' => $instructeurList]);
    }

    public function gebruikteVoertuigen(Instructeur $instructeur)
    {
        $instructeurId = $instructeur->id;

        $voertuigGegevens = Instructeur::join('voertuigInstructeurs', 'instructeurs.id', '=', 'voertuigInstructeurs.instructeursId')
            ->join('voertuigs', 'voertuigInstructeurs.voertuigsId', '=', 'voertuigs.id')
            ->join('typeVoertuigs', 'voertuigs.typeVoertuigsid', '=', 'typeVoertuigs.id')
            ->where('instructeurs.id', '=', $instructeurId)
            ->orderBy('typeVoertuigs.rijbewijsCategorie', 'asc')
            ->get();
        return view('instructeur.gebruikteVoertuigen', ['instructeurs' => $instructeur, 'voertuigGegevens' => $voertuigGegevens]);
    }
}
