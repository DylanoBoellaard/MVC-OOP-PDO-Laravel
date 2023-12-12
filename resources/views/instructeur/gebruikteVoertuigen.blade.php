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
        <h1>Door instructeur gebruikte voertuigen</h1>

        <!-- If statement to display the success message -->
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @elseif (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
        @endif

        <!-- Instructeur naam & redirect links -->
        <div id="instructeurList">
            <p><span>Naam:</span> {{$instructeurs->voornaam}} {{$instructeurs->tussenvoegsel}} {{$instructeurs->achternaam}}</p>
            <p><span>Datum in dienst:</span> {{$instructeurs->datumInDienst}}</p>
            <p><span>Aantal sterren:</span> {{$instructeurs->aantalSterren}}</p>
            <a href="{{route('instructeur.beschikbareVoertuigen', [$instructeurs->id])}}">Toevoegen voertuig</a>
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
                    <th>Wijzigen</th>
                    <th>Verwijderen</th>
                    <th>Toegewezen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($voertuigGegevens as $voertuig) <!-- Foreach Loop to display all vehicle details -->
                <tr>
                    <td>{{$voertuig->typeVoertuig}}</td>
                    <td>{{$voertuig->type}}</td>
                    <td>{{$voertuig->kenteken}}</td>
                    <td>{{$voertuig->bouwjaar}}</td>
                    <td>{{$voertuig->brandstof}}</td>
                    <td>{{$voertuig->rijbewijsCategorie}}</td>
                    <td><a href="{{route('instructeur.wijzigenVoertuigen', [$instructeurs->id, $voertuig->id])}}"><img src="/img/Edit-icon.png" alt="wijzigenVoertuig.png"></a></td>
                    <td><a href="{{route('instructeur.unassign', [$instructeurs->id, $voertuig->id])}}"><img src="/img/Delete-icon.png" alt="verwijderVoertuig.png"></a></td>
                    @foreach($vehicleData as $data)
                        @if($data->voertuigsId == $voertuig->id)
                            @if($data->amount > 1)
                                <td>
                                    <a href="{{route('instructeur.addtoElse', [$instructeurs->id, $voertuig->id])}}">
                                        <img class="small-img" src="/img/RedCross.png" alt="Redcross.png">
                                    </a>
                                </td>
                            @else
                                <td>
                                    <img class="small-img" src="/img/GreenCheckmark.png" alt="GreenCheckmark.png">
                                </td>
                            @endif
                        @endif
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>