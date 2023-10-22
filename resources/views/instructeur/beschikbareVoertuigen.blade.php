<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikte voertuigen</title>

    @vite(['resources/scss/instructeur/gebruikteVoertuigen.scss', 'resources/scss/instructeur/global.scss'])
</head>
<body>
    <div id="container">
        <h1>Alle beschikbare voertuigen</h1>

        <!-- Instructeur naam & redirect links -->
        <div id="instructeurList">
            <p><span>Naam:</span> {{$instructeurs->voornaam}} {{$instructeurs->tussenvoegsel}} {{$instructeurs->achternaam}}</p>
            <p><span>Datum in dienst:</span> {{$instructeurs->datumInDienst}}</p>
            <p><span>Aantal sterren:</span> {{$instructeurs->aantalSterren}}</p>

            <a href="{{route('instructeur.index')}}">Terug naar instructeur lijst</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Type voertuig</th>
                    <th>Type</th>
                    <th>Kenteken</th>
                    <th>Bouwjaar</th>
                    <th>Brandstof</th>
                    <th>Rijbewijscategorie</th>
                    <th>Toevoegen</th>
                    <th>Wijzigen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unassignedVehicles as $voertuig) <!-- Foreach Loop to display all vehicle details -->
                <tr>
                    <td>{{$voertuig->typeVoertuig}}</td>
                    <td>{{$voertuig->type}}</td>
                    <td>{{$voertuig->kenteken}}</td>
                    <td>{{$voertuig->bouwjaar}}</td>
                    <td>{{$voertuig->brandstof}}</td>
                    <td>{{$voertuig->rijbewijsCategorie}}</td>
                    <td><a href="{{route('instructeur.addVehicle', [$instructeurs->id, $voertuig->id])}}"><img src="/img/Plus-button.png" alt="toevoegenVoertuig.png"></a></td>
                    <td><a href="{{route('instructeur.wijzigenVoertuigen', [$instructeurs->id, $voertuig->id])}}"><img src="/img/Edit-icon.png" alt="wijzigenVoertuig.png"></a></td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>