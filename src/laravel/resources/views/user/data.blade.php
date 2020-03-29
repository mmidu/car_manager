<div class="user {{$old_owner ? 'old_owner' : 'new_owner hide' }}">
    <div>{{$old_owner ? 'PROPRIETARIO' : 'NUOVO PROPRIETARIO'}}</div>
    <label for="first_name">{{ __('Nome') }}</label>
    <input type="text" name="{{ $old_owner ? '' : 'first_name' }}" value="{{ $old_owner ? ($user->first_name ?? null) : '' }}" required autocomplete="first_name" autofocus>
    
    <label for="last_name">{{ __('Cognome') }}</label>
    <input type="text" name="{{ $old_owner ? '' : 'last_name' }}" value="{{ $old_owner ? ($user->last_name ?? null) : '' }}" required autocomplete="last_name" autofocus>
    
    <label for="birth_date">{{ __('Data di nascita') }}</label>
    <input type="date" name="{{ $old_owner ? '' : 'birth_date' }}" value="{{$old_owner ? (isset($user->birth_date) ? $user->birth_date->format('Y-m-d') : null) : ''}}" max="{{ now()->format('Y-m-d')}}" required autocomplete="birth_date" autofocus>
    
    <label for="fiscal_code">{{ __('Codice fiscale') }}</label>
    <input type="text" name="{{ $old_owner ? '' : 'fiscal_code' }}" value="{{ $old_owner ? ($user->fiscal_code ?? null) : '' }}" required readonly>
    
    <label for="address">{{ __('Indirizzo') }}</label>
    <input type="text" name="{{ $old_owner ? '' : 'address' }}" value="{{ $old_owner ? ($user->address ?? null) : '' }}" required autocomplete="address" autofocus>
    
    @if(!$old_owner)
    <label for="gender">{{ __('Genere') }}</label>
    <select name="gender" required>
        <option>Seleziona</option>
        <option value="40">F</option>
        <option value="0">M</option>
    </select>
    @endif
</div>