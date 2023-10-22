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

        $voertuigGegevens = Voertuig::select('voertuigs.id', 'voertuigs.type', 'voertuigs.kenteken', 'voertuigs.bouwjaar', 'voertuigs.brandstof', 'typeVoertuigs.typeVoertuig', 'typeVoertuigs.rijbewijsCategorie')
            ->join('voertuigInstructeurs', 'voertuigs.id', '=', 'voertuigInstructeurs.voertuigsId')
            ->join('instructeurs', 'voertuigInstructeurs.instructeursId', '=', 'instructeurs.id')
            ->join('typeVoertuigs', 'voertuigs.typeVoertuigsId', '=', 'typeVoertuigs.id')
            ->where('instructeurs.id', $instructeurId)
            ->orderBy('typeVoertuigs.rijbewijsCategorie', 'asc')
            ->get();

        return view('instructeur.gebruikteVoertuigen', ['instructeurs' => $instructeur, 'voertuigGegevens' => $voertuigGegevens]);
    }

    public function wijzigenVoertuigen(Instructeur $instructeur, $voertuig)
    {
        $instructeurList = Instructeur::all();
        $typeVoertuigList = TypeVoertuig::select('id', 'typeVoertuig')->get();

        $voertuigGegevens = DB::table('voertuigs')
            ->select('voertuigInstructeurs.*', 'voertuigs.id', 'voertuigs.type', 'voertuigs.kenteken', 'voertuigs.bouwjaar', 'voertuigs.brandstof', 'voertuigs.typeVoertuigsId', 'typeVoertuigs.rijbewijscategorie', 'typeVoertuigs.typeVoertuig')
            ->leftJoin('voertuigInstructeurs', 'voertuigs.id', '=', 'voertuigInstructeurs.voertuigsId')
            ->join('typeVoertuigs', 'voertuigs.typeVoertuigsId', '=', 'typeVoertuigs.id')
            ->where('voertuigs.id', $voertuig)
            ->get();

        return view('instructeur.wijzigenVoertuigen', [
            'instructeurs' => $instructeur,
            'voertuigGegevens' => $voertuigGegevens,
            'instructeurList' => $instructeurList,
            'typeVoertuigList' => $typeVoertuigList,
            'voertuigId' => $voertuig,
        ]);
    }

    public function update(Request $request, Instructeur $instructeur, Voertuig $voertuig)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'instructeur' => 'required|exists:instructeurs,id',
            'typeVoertuig' => 'required|exists:typeVoertuigs,id',
            'type' => 'required',
            'bouwjaar' => 'required|date',
            'brandstof' => 'required|in:Diesel,Benzine,Elektrisch',
            'kenteken' => 'required|max:50',
        ]);

        // Find the vehicle by its ID
        $voertuig = Voertuig::findOrFail($voertuig->id);

        // Update the vehicle data
        $voertuig->type = $validatedData['type'];
        $voertuig->bouwjaar = $validatedData['bouwjaar'];
        $voertuig->brandstof = $validatedData['brandstof'];
        $voertuig->kenteken = $validatedData['kenteken'];
        $voertuig->typeVoertuigsId = $validatedData['typeVoertuig'];

        // Save the changes to the database
        $voertuig->save();

        // Update the relationship with the instructor
        $voertuigInstructeur = VoertuigInstructeur::where('voertuigsId', $voertuig->id)
            ->update(['instructeursId' => $validatedData['instructeur']]);

        // Redirect to a success page or return a response
        return redirect()->route('instructeur.gebruikteVoertuigen', ['instructeur' => $instructeur])
            ->with('success', 'Vehicle data updated successfully.');
    }
}
