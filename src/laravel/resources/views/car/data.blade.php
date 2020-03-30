<div class="car {{ $car->empty ?: 'disabled' }} form_group">
    <div>AUTO</div>
    <div class="form_row">
        <div>
            <label class="form_el_title" for="model">{{ __('Modello') }}</label>
            <input class="form_el_input" id="model" type="text" name="model" value="{{ $car->model ?? null }}" required autocomplete="model">
        </div>

        <div>
            <label class="form_el_title" for="manufacturer">{{ __('Marca') }}</label>
            <input class="form_el_input" id="manufacturer" type="text" name="manufacturer" value="{{ $car->manufacturer ?? null }}" required autocomplete="manufacturer">
        </div>

        <div>
            <label class="form_el_title" for="year">{{ __('Anno') }}</label>
            <input class="form_el_input" id="year" type="date" name="year" value="{{isset($car->year) ? $car->year->format('Y-m-d') : null}}" max="{{ now()->format('Y-m-d')}}" required autocomplete="year">
        </div>
    </div>

    <div class="form_row">
        <div>
            <label class="form_el_title" for="license_plate">{{ __('Targa') }}</label>
            <input class="form_el_input" id="license_plate" name="license_plate" type="text" value="{{ $car->license_plate ?? null }}" required readonly>
        </div>
        
        <div>
            <label class="form_el_title" for="engine_displacement">{{ __('Cilindrata') }}</label>
            <input class="form_el_input" id="engine_displacement" type="number" name="engine_displacement" value="{{ $car->engine_displacement ?? null }}" required autocomplete="engine_displacement">
        </div>
        
        <div>
            <label class="form_el_title" for="horse_power">{{ __('Cavalli') }}</label>
            <input class="form_el_input" id="horse_power" type="number" name="horse_power" value="{{ $car->horse_power ?? null }}" required autocomplete="horse_power">
        </div>
    </div>
</div>