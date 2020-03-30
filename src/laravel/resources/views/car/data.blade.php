<div class="car {{ $car->empty ?: 'disabled' }}">
    <div>AUTO</div>
    <label for="model">{{ __('Modello') }}</label>
    <input id="model" type="text" name="model" value="{{ $car->model ?? null }}" required autocomplete="model">

    <label for="manufacturer">{{ __('Marca') }}</label>
    <input id="manufacturer" type="text" name="manufacturer" value="{{ $car->manufacturer ?? null }}" required autocomplete="manufacturer">

    <label for="year">{{ __('Anno') }}</label>
    <input id="year" type="date" name="year" value="{{isset($car->year) ? $car->year->format('Y-m-d') : null}}" max="{{ now()->format('Y-m-d')}}" required autocomplete="year">

    <label for="license_plate">{{ __('Targa') }}</label>
    <input id="license_plate" name="license_plate" type="text" value="{{ $car->license_plate ?? null }}" required readonly>
    
    <label for="engine_displacement">{{ __('Cilindrata') }}</label>
    <input id="engine_displacement" type="number" name="engine_displacement" value="{{ $car->engine_displacement ?? null }}" required autocomplete="engine_displacement">
    
    <label for="horse_power">{{ __('Cavalli') }}</label>
    <input id="horse_power" type="number" name="horse_power" value="{{ $car->horse_power ?? null }}" required autocomplete="horse_power">
</div>