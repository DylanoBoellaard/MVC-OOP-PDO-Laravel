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

        <!-- If statement to display the success or error message -->
        @if (session('success') || session('error'))
        <div class="alert {{ session('error') ? 'alert-error' : 'alert-success' }}">
            {{ session('error') ? session('error') : session('success') }} (<span id="countdown">3</span>)
        </div>
        <script>
            let countdown = 3; // Set the initial countdown time (in seconds)

            function updateCountdown() {
                countdown -= 1;
                document.getElementById('countdown').textContent = countdown;

                if (countdown <= 0) {
                    // Hide the message when the countdown reaches 0 seconds
                    document.querySelector('.alert').style.display = 'none';
                } else {
                    // Update the countdown every 1 second
                    setTimeout(updateCountdown, 1000);
                }
            }

            // Start the countdown when the page loads
            setTimeout(updateCountdown, 1000);
        </script>
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
                    <th>Ziekte / Verlof</th>
                    <th>Verwijderen</th>
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
                    <td>
                        <form action="{{ route('instructeur.deleteInstructor', [$instructeur->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; padding: 0; margin: 0; cursor: pointer;">
                                <img class="small-img" src="/img/Delete-Icon.png" alt="Delete_Icon.png">
                            </button>
                        </form>
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>