<div class="user {{$old_owner ? 'old_owner' : 'new_owner hide' }} form_group">
    <div>{{$old_owner ? 'PROPRIETARIO' : 'NUOVO PROPRIETARIO'}}</div>
    <div class="form_row">
        <div>
            <label class="form_el_title" for="first_name">{{ __('Nome') }}</label>
            <input class="form_el_input" type="text" name="{{ $old_owner ? '_first_name' : 'first_name' }}" value="{{ $old_owner ? ($user->first_name ?? null) : '' }}" required autocomplete="first_name">
        </div>

        <div>
            <label class="form_el_title" for="last_name">{{ __('Cognome') }}</label>
            <input class="form_el_input" type="text" name="{{ $old_owner ? '_last_name' : 'last_name' }}" value="{{ $old_owner ? ($user->last_name ?? null) : '' }}" required autocomplete="last_name">
        </div>

        <div>
            <label class="form_el_title" for="birth_date">{{ __('Data di nascita') }}</label>
            <input class="form_el_input" type="date" name="{{ $old_owner ? '_birth_date' : 'birth_date' }}" value="{{$old_owner ? (isset($user->birth_date) ? $user->birth_date->format('Y-m-d') : null) : ''}}" max="{{ now()->format('Y-m-d')}}" required autocomplete="birth_date">
        </div>
    </div>

    <div class="form_row">
        <div>
            <label class="form_el_title" for="fiscal_code">{{ __('Codice fiscale') }}</label>
            <input class="form_el_input" type="text" name="{{ $old_owner ? '_fiscal_code' : 'fiscal_code' }}" class="{{$old_owner ? 'disabled' : ''}}"value="{{ $old_owner ? ($user->fiscal_code ?? null) : '' }}" required {{ $old_owner ? 'readonly' : '' }}>
        </div>
        
        <div>
            <label class="form_el_title" for="address">{{ __('Indirizzo') }}</label>
            <input class="form_el_input" type="text" name="{{ $old_owner ? '_address' : 'address' }}" value="{{ $old_owner ? ($user->address ?? null) : '' }}" required autocomplete="address">
        </div>
    </div>
</div>