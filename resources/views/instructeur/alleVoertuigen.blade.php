<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle voertuigen</title>

    @vite(['resources/scss/instructeur/alleVoertuigen.scss', 'resources/scss/instructeur/global.scss'])
</head>

<body>
    <div id="container">
        <h1>Alle voertuigen</h1>

        <a href="{{route('instructeur.index')}}">Terug naar instructeur lijst</a>

        <!-- If statement to display the success message -->
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if (isset($error)) <!-- elseif statement to display an error message -->
        <div class="alert alert-error">
            {{ $error }}
        </div>
        <div id="redirect">
            <p>Redirecting in <span id="countdown">3</span> seconds.</p>
        </div>
        <script>
            let countdown = 3; // Set the initial countdown time (in seconds)

            function updateCountdown() {
                countdown -= 1;
                document.getElementById('countdown').textContent = countdown;

                if (countdown <= 0) {
                    // Redirect the user to the homepage the countdown has reached 0 seconds (or less).
                    window.location.href = "{{ route('instructeur.index') }}";
                } else {
                    // Update the countdown every 1 second
                    setTimeout(updateCountdown, 1000);
                }
            }

            // Start the countdown when the page loads
            setTimeout(updateCountdown, 1000);
        </script>
        @endif


        <!-- Check if $voertuigGegevens is not empty, if not empty > display table -->
        @if (isset($voertuigGegevens) && !$voertuigGegevens->isEmpty())
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
        @endif
    </div>
</body>

</html>