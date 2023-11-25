<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructeur lijst</title>

    @vite(['resources/scss/instructeur/index.scss', 'resources/scss/instructeur/global.scss'])
</head>

<body>
    <div id="container">
        <h1>Instructeurs in dienst</h1>
        <p><span>Aantal instructeurs: </span>{{$aantalInstructeurs}}</p> <!-- Show total amount of instructors -->
        <a href="{{route('instructeur.alleVoertuigen')}}">Alle voertuigen</a>

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

        <table>
            <thead>
                <tr>
                    <th>Voornaam</th>
                    <th>Tussenvoegsel</th>
                    <th>Achternaam</th>
                    <th>Mobiel</th>
                    <th>Datum in dienst</th>
                    <th>Aantal sterren</th>
                    <th>Voertuigen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($instructeurList as $instructeur) <!-- Foreach Loop to display all instructor details -->
                <tr>
                    <td>{{$instructeur->voornaam}}</td>
                    <td>{{$instructeur->tussenvoegsel}}</td>
                    <td>{{$instructeur->achternaam}}</td>
                    <td>{{$instructeur->mobiel}}</td>
                    <td>{{$instructeur->datumInDienst}}</td>
                    <td>{{$instructeur->aantalSterren}}</td>
                    <td><a href="{{route('instructeur.gebruikteVoertuigen', [$instructeur-> id])}}"><img src="/img/Car-logo-transparent.png" alt="voertuig.png"></a></td>
                    @if($instructeur->isActief == true)
                    <td>
                        <a href="{{route('instructeur.notActive', [$instructeur->id])}}">
                            <img class="small-img" src="/img/ThumbsUp.png" alt="thumb.png">
                        </a>
                    </td>
                    @else
                    <td>
                        <a href="{{route('instructeur.active', [$instructeur->id])}}">
                            <img class="small-img" src="/img/Bandage.png" alt="band_aid.png">
                        </a>
                    </td>
                    @endif
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>