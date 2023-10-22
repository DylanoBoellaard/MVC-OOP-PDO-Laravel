<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle voertuigen</title>

    @vite(['resources/scss/instructeur/gebruikteVoertuigen.scss', 'resources/scss/instructeur/global.scss'])
</head>
<body>
    <div id="container">
        <h1>Alle voertuigen</h1>

        <!-- If statement to display the success message -->
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        

        
        
        <table>
            <thead>
                <tr>
                    <th>Type voertuig</th>
                    <th>Type</th>
                    <th>Kenteken</th>
                    <th>Bouwjaar</th>
                    <th>Brandstof</th>
                    <th>Rijbewijscategorie</th>
                    <th>Instructeur naam</th>
                    <th>Verwijderen</th>
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
                    <td>
                        @if ($voertuig->instructeursId)
                            {{$voertuig->voornaam}} {{$voertuig->tussenvoegsel}} {{$voertuig->achternaam}}
                        @endif
                    </td>
                    <td><a href="{{route('instructeur.delete', [$voertuig->id])}}"><img src="/img/Delete-icon.png" alt="verwijderVoertuig.png"></a></td>
                    @endforeach
                </tr>
            </tbody>
        </table>
        
    </div>
</body>
</html>