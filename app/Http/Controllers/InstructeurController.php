<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instructeur;         // Import models
use App\Models\Voertuig;
use App\Models\TypeVoertuig;
use App\Models\VoertuigInstructeur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class InstructeurController extends Controller
{
    // Show all instructors
    public function index()
    {
        $aantalInstructeurs = Instructeur::distinct()->count('id');

        $instructeurList = DB::table('instructeurs')
            ->select('id', 'voornaam', 'tussenvoegsel', 'achternaam', 'mobiel', 'datumInDienst', 'aantalSterren', 'isActief')
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

        $voertuigGegevens = Voertuig::select('voertuigs.id', 'voertuigs.type', 'voertuigs.kenteken', 'voertuigs.bouwjaar', 'voertuigs.brandstof', 'typeVoertuigs.typeVoertuig', 'typeVoertuigs.rijbewijsCategorie', 'voertuigInstructeurs.isActief')
            ->join('voertuigInstructeurs', 'voertuigs.id', '=', 'voertuigInstructeurs.voertuigsId')
            ->join('instructeurs', 'voertuigInstructeurs.instructeursId', '=', 'instructeurs.id')
            ->join('typeVoertuigs', 'voertuigs.typeVoertuigsId', '=', 'typeVoertuigs.id')
            ->where('instructeurs.id', $instructeurId)
            ->orderBy('typeVoertuigs.rijbewijsCategorie', 'asc')
            ->get();

        $vehicleData = DB::table('voertuigInstructeurs')->select('voertuigsId',  DB::raw('count(instructeursId) as amount'))->groupBy('voertuigsId')->get();

        return view('instructeur.gebruikteVoertuigen', ['instructeurs' => $instructeur, 'voertuigGegevens' => $voertuigGegevens, 'vehicleData' => $vehicleData]);
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

        // Redirect to the gebruikteVoertuigen page with a success message
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

        // Redirect to the gebruikteVoertuigen page with a success message
        return redirect()->route('instructeur.gebruikteVoertuigen', ['instructeur' => $instructeur])
            ->with('success', 'Voertuig succesvol toegevoegd.');
    }

    // Method to unassign the selected vehicle from the selected instructor
    public function unassign(Instructeur $instructeur, Voertuig $voertuig)
    {
        // Check if the vehicle is assigned to the instructor
        $voertuigInstructeur = VoertuigInstructeur::where('instructeursId', $instructeur->id)
            ->where('voertuigsId', $voertuig->id)
            ->first();

        // If assigned, unassign the vehicle from the instructor
        if ($voertuigInstructeur) {
            $voertuigInstructeur->delete();

            // Redirect back to the gebruikteVoertuigen page with a success message if successful
            return redirect()->route('instructeur.gebruikteVoertuigen', ['instructeur' => $instructeur])
                ->with('success', 'Voertuig is succesvol ontkoppeld van de instructeur.');
        } else {
            // If the vehicle isn't assigned, redirect back to the gebruikteVoertuigen page with an error message
            return redirect()->route('instructeur.gebruikteVoertuigen', ['instructeur' => $instructeur])
                ->with('error', 'Error, dit voertuig is niet toegewezen aan de instructeur.');
        }
    }

    // Method for showing all vehicles
    public function alleVoertuigen()
    {
        $voertuigGegevens = Voertuig::select('voertuigs.id', 'voertuigs.type', 'voertuigs.kenteken', 'voertuigs.bouwjaar', 'voertuigs.brandstof', 'typeVoertuigs.typeVoertuig', 'typeVoertuigs.rijbewijsCategorie', 'instructeurs.id as instructeursId', 'instructeurs.voornaam', 'instructeurs.tussenvoegsel', 'instructeurs.achternaam')
            ->leftJoin('voertuigInstructeurs', 'voertuigs.id', '=', 'voertuigInstructeurs.voertuigsId')
            ->leftJoin('instructeurs', 'voertuigInstructeurs.instructeursId', '=', 'instructeurs.id')
            ->join('typeVoertuigs', 'voertuigs.typeVoertuigsId', '=', 'typeVoertuigs.id')
            ->orderBy('voertuigs.bouwjaar', 'desc')
            ->orderBy('instructeurs.achternaam', 'asc')
            ->get();

        if ($voertuigGegevens->isEmpty()) {
            $error = 'Er zijn geen voertuigen beschikbaar op dit moment.';
            return view('instructeur.alleVoertuigen', ['voertuigGegevens' => $voertuigGegevens, 'error' => $error]);
        }

        return view('instructeur.alleVoertuigen', ['voertuigGegevens' => $voertuigGegevens]);
    }

    // Method for deleting vehicles
    public function delete(Voertuig $voertuig)
    {
        // Delete the vehicle
        $voertuig->delete();

        // Redirect back to the gebruikteVoertuigen page with a success message
        return redirect()->route('instructeur.alleVoertuigen', ['voertuig' => $voertuig])
            ->with('success', 'Voertuig is succesvol verwijderd.');
    }

    // Make instructor not active & remove assigned vehicles
    public function notActive(Instructeur $instructeur)
    {
        $instructeurId = $instructeur->id;

        $activeInfo = DB::table('instructeurs')->select('isActief')->where('id', $instructeurId)->get();
        if ($activeInfo[0]->isActief == true) {
            $active = array(
                'isActief' => 0
            );

            DB::table('instructeurs')->where('id', $instructeurId)->update($active);

            DB::table('voertuigInstructeurs')->where('Instructeursid', $instructeurId)->delete();
        }
        return redirect(route('instructeur.index'))->with('success', $instructeur->voornaam . ' is onactief gemaakt');
    }

    // Make instructor active again and reassign previously unassigned vehicles
    public function active(Instructeur $instructeur)
    {
        $instructeurId = $instructeur->id;

        $activeInfo = DB::table('instructeurs')->select('isActief')->where('id', $instructeurId)->get();
        if ($activeInfo[0]->isActief == false) {
            $active = array(
                'isActief' => 1
            );
            DB::table('instructeurs')->where('id', $instructeurId)->update($active);


            $saveVid = DB::table('savedVoertuigs')->select('voertuigsId')->where('instructeursId', $instructeurId)->get();
            $DatumToekenning = date('y-m-d');
            $DatumAangemaakt = date('y-m-d h:i:s');
            $DatumGewijzigd = date('y-m-d h:i:s');
            foreach ($saveVid as $key) {
                $voertuigGegevens = $key->voertuigsId;
                $data = VoertuigInstructeur::insert(array(
                    'voertuigsId' => $voertuigGegevens,
                    'instructeursId' => $instructeurId,
                    'datumToekenning' => $DatumToekenning,
                    'created_at' => $DatumAangemaakt,
                    'updated_at' => $DatumGewijzigd
                ));
            }
        }
        return redirect(route('instructeur.index'))->with('success', $instructeur->voornaam . ' is actief gemaakt');
    }

    // Function to add
    public function addVehicleToSomeoneElse(Instructeur $instructeur, $voertuig)
    {
        $id = $instructeur->id;

        $vehicleData = DB::table('voertuigInstructeurs')->select('voertuigsId', 'instructeursId')->where('instructeursId', '!=', $id)->get();
        if ($vehicleData->isEmpty()) {
        } else {
            DB::table('voertuigInstructeurs')->select('voertuigsId', 'instructeursId')->where('instructeursId', '!=', $id)->where('voertuigsId', '=', $voertuig)->delete();
            DB::table('savedVoertuigs')->select('voertuigsId', 'instructeursId')->where('instructeursId', '!=', $id)->where('voertuigsId', '=', $voertuig)->delete();

            return redirect(route('instructeur.gebruikteVoertuigen', [$id]))->with('success', 'Het geselecteerde voertuig is weer toegewezen aan ' . $instructeur->voornaam);
        }
    }

    // Function to delete instructor WIP
    public function deleteInstructor(Instructeur $instructeur)
    {
        $id = $instructeur->id;
        $aantalInstructeurs = Instructeur::distinct()->count('id');

        $instructeurList = DB::table('instructeurs')
            ->select('id', 'voornaam', 'tussenvoegsel', 'achternaam', 'mobiel', 'datumInDienst', 'aantalSterren', 'isActief')
            ->orderBy('aantalSterren', 'desc')
            ->get();

        // Detach cars associated with the instructor
        VoertuigInstructeur::where('instructeursId', $id)->delete();

        // Now, delete the instructor
        $instructeur->delete();

        return redirect()->route('instructeur.index', ['instructeurList' => $instructeurList, 'aantalInstructeurs' => $aantalInstructeurs])
                ->with('success', 'Instructeur ' . `$instructeur->voornaam $instructeur->tussenvoegsel $instructeur->achternaam` . ' is definitief verwijdert en al zijn eerder toegewezen voertuigen zijn vrijgegeven.');
    }
}
