<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wijzigen voertuigen</title>
    @vite(['resources/scss/instructeur/gebruikteVoertuigen.scss', 'resources/scss/instructeur/global.scss'])
</head>

<body>
    <div id="container">
        <h1>Wijzigen voertuiggegevens</h1>
        <a href="{{route('instructeur.index')}}">Terug naar instructeur lijst</a>

        @foreach($voertuigGegevens as $voertuig)
        <form method="post" action="{{route('instructeur.update', ['instructeur' => $instructeurs->id, 'voertuig' => $voertuig->id])}}">
            @csrf
            @method('put')

            <div id="formField">
                <label for="instructeur">Instructeur:</label>
                <input type="hidden" name="voertuig" value="{{ $voertuig->id }}"> <!-- Used to send vehicle ID to update method to assign unassigned vehicle to selected instructor -->
                <select name="instructeur" id="instructeur">
                    @foreach($instructeurList as $instructeurOption)
                        <option value="{{ $instructeurOption->id }}" {{ $instructeurOption->id == $instructeurs->id ? 'selected' : '' }}>{{ $instructeurOption->voornaam }} {{ $instructeurOption->achternaam }}</option>
                    @endforeach
                </select>
            </div>

            <div id="formField">
                <label for="typeVoertuig">Type voertuig:</label>
                <select name="typeVoertuig" id="typeVoertuig">
                    @foreach($typeVoertuigList as $typeVoertuigOption)
                        <option value="{{ $typeVoertuigOption->id }}" {{ $typeVoertuigOption->id == $voertuig->typeVoertuigsId ? 'selected' : '' }}>{{ $typeVoertuigOption->typeVoertuig }}</option>
                    @endforeach
                </select>
            </div>

            <div id="formField"> <!-- NOT CORRECT VEHICLE -->
                <label for="type">Type:</label>
                    <input type="text" name="type" id="type" value="{{$voertuig->type}}">
            </div>

            <div id="formField">
                <label for="bouwjaar">Bouwjaar:</label>
                    <input type="text" name="bouwjaar" id="bouwjaar" value="{{$voertuig->bouwjaar}}">
            </div>

            <div id="formField">
                <label for="brandstof">Brandstof:</label>
                <label for="diesel">Diesel</label>
                    <input type="radio" name="brandstof" id="diesel" value="Diesel" {{ $voertuig->brandstof == 'Diesel' ? 'checked' : '' }}>
                <label for="benzine">Benzine</label>
                    <input type="radio" name="brandstof" id="benzine" value="Benzine" {{ $voertuig->brandstof == 'Benzine' ? 'checked' : '' }}>
                <label for="elektrisch">Elektrisch</label>
                    <input type="radio" name="brandstof" id="elektrisch" value="Elektrisch" {{ $voertuig->brandstof == 'Elektrisch' ? 'checked' : '' }}>
            </div>

            <div id="formField">
                <label for="kenteken">Kenteken:</label>
                <input type="text" name="kenteken" id="kenteken" value="{{$voertuig->kenteken}}">
            </div>

            @endforeach

            <div id="submit">
                <input type="submit" value="Wijzigen">
            </div>

        </form>
    </div>
</body>
</html>