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
        $aantalInstructeurs = Instructeur::distinct()->count('id');

        $instructeurList = DB::table('instructeurs')
            ->select('id', 'voornaam', 'tussenvoegsel', 'achternaam', 'mobiel', 'datumInDienst', 'aantalSterren')
            ->orderBy('aantalSterren', 'desc')
            ->get();

        return view('instructeur.index', [
            'instructeurList' => $instructeurList,
            'aantalInstructeurs' => $aantalInstructeurs,
        ]);
    }

    // Method for showing vehicles already assigned to instructor
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

    // Method for showing selected vehicle editing page
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

    // Method for actually processing and updating the vehicle values
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

        // Allow unassigned vehicle to update vehicle data and assign to selected instructor
        // Check if the instructor has been changed in the form
        if ($request->has('instructeur')) {
            $instructeurId = $request->input('instructeur');
            $voertuigId = $request->input('voertuig');
        // Update the relationship with the instructor
            $voertuigInstructeur = VoertuigInstructeur::updateOrCreate(
                ['voertuigsId' => $voertuigId],
                ['instructeursId' => $instructeurId]
            );
        }

        // Redirect to a gebruikteVoertuigen page with a success message
        return redirect()->route('instructeur.gebruikteVoertuigen', ['instructeur' => $instructeur])
            ->with('success', 'Voertuig data succesvol geupdate.');
    }

    // Method for showing all non-assigned available vehicles on a page
    public function beschikbareVoertuigen(Instructeur $instructeur)
    {
        $unassignedVehicles = Voertuig::select('voertuigs.id', 'voertuigs.type', 'voertuigs.kenteken', 'voertuigs.bouwjaar', 'voertuigs.brandstof', 'typeVoertuigs.typeVoertuig', 'typeVoertuigs.rijbewijsCategorie')
            ->join('voertuigInstructeurs', 'voertuigs.id', '=', 'voertuigInstructeurs.voertuigsId', 'left')
            ->join('typeVoertuigs', 'voertuigs.typeVoertuigsId', '=', 'typeVoertuigs.id')
            ->whereNull('voertuigInstructeurs.voertuigsId')
            ->orderBy('voertuigs.id', 'asc')
            ->get();

        return view('instructeur.beschikbareVoertuigen', [
            'instructeurs' => $instructeur,
            'unassignedVehicles' => $unassignedVehicles
        ]);
    }

    // Method to actually add the selected vehicle and instructor to the database
    public function addVehicle(Instructeur $instructeur, Voertuig $voertuig)
    {
        // Variables
        $instructeurId = $instructeur->id;
        $voertuigId = $voertuig->id;
        $datumToekenning = date('y-m-d');
        $createdAt = date('y-m-d h:i:s');
        $updatedAt = date('y-m-d h:i:s');

        // Insert to DB query
        $vehicleData = VoertuigInstructeur::insert(array(
            'voertuigsId' => $voertuigId,
            'instructeursId' => $instructeurId,
            'datumToekenning' => $datumToekenning,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ));

        // Redirect to a gebruikteVoertuigen page with a success message
        return redirect()->route('instructeur.gebruikteVoertuigen', ['instructeur' => $instructeur])
            ->with('success', 'Voertuig succesvol toegevoegd.');
    }
}
