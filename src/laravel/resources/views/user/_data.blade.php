<div class="{{ $type == 'old_owner' ? 'old_owner' : 'hide new_owner'}}">
    <div class="card-header" style="grid-area: t">{{ $type  == 'old_owner' ? 'PROPRIETARIO' : 'NUOVO PROPRIETARIO'}}</div>

                        <div class="form-group row" style="grid-area: a">
                            <span class="col-md-4 three_col">
                                <label for="{{ $type }}_first_name" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>
                                <input id="{{ $type }}_first_name" type="text" class="form-control @error('$type_first_name') is-invalid @enderror" name="{{ $type }}_first_name" value="{{ $$type->first_name ?? null }}" required autocomplete="{{ $type }}_first_name" autofocus>

                                @error('$type_first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>

        

                            <span class="col-md-4 three_col">
                                <label for="{{ $type }}_last_name" class="col-md-4 col-form-label text-md-right">{{ __('Cognome') }}</label>
                                <input id="{{ $type }}_last_name" type="text" class="form-control @error('$type_last_name') is-invalid @enderror" name="{{ $type }}_last_name" value="{{ $$type->last_name ?? null }}" required autocomplete="{{ $type }}_last_name" autofocus>

                                @error('$type_last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>

                            

                            <span class="col-md-4 three_col">
                                <label for="{{ $type }}_birth_date" class="col-md-4 col-form-label text-md-right">{{ __('Data di nascita') }}</label>
                                <input id="{{ $type }}_birth_date" type="date" class="form-control @error('$type_birth_date') is-invalid @enderror" name="{{ $type }}_birth_date" value="{{ $$type->birth_date ? $$type->birth_date->format('Y-m-d') : null }}"  max="{{ now()->format('Y-m-d')}}" required autocomplete="{{ $type }}_birth_date" autofocus>

                                @error('$type_birth_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>
                        </div>

                        <div class="form-group row" style="grid-area: b">
                            <span class="col-md-4 three_col">
                                <label for="{{ $type }}_gender" class="col-md-4 col-form-label text-md-right">{{ __('Sesso') }}</label>
                                <select id="{{ $type }}_gender" name="{{ $type }}_gender">
                                    <option>Seleziona</option>
                                    <option value="40">F</option>
                                    <option value="0">M</option>
                                </select>
                            </span>


                            <span class="col-md-4 three_col">
                                <label for="{{ $type }}_fiscal_code" class="col-md-4 col-form-label text-md-right">{{ __('Codice fiscale') }}</label>
                                <input id="{{ $type }}_fiscal_code" type="text" class="form-control upper @error('$type_fiscal_code') is-invalid @enderror" value="{{ $$type->fiscal_code ?? null }}" required pattern="(?:[A-Z][AEIOU][AEIOUX]|[B-DF-HJ-NP-TV-Z]{2}[A-Z]){2}(?:[\dLMNP-V]{2}(?:[A-EHLMPR-T](?:[04LQ][1-9MNP-V]|[15MR][\dLMNP-V]|[26NS][0-8LMNP-U])|[DHPS][37PT][0L]|[ACELMRT][37PT][01LM]|[AC-EHLMPR-T][26NS][9V])|(?:[02468LNQSU][048LQU]|[13579MPRTV][26NS])B[26NS][9V])(?:[A-MZ][1-9MNP-V][\dLMNP-V]{2}|[A-M][0L](?:[1-9MNP-V][\dLMNP-V]|[0L][1-9MNP-V]))[A-Z]">

                                @error('$type_fiscal_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>

        

                            <span class="col-md-4 three_col">
                                <label for="{{ $type }}_address" class="col-md-4 col-form-label text-md-right">{{ __('Indirizzo') }}</label>
                                <input id="{{ $type }}_address" type="text" class="form-control @error('{{ $type }}_address') is-invalid @enderror" name="{{ $type }}_address" value="{{ $$type->address ?? null }}" required autocomplete="{{ $type }}_address" autofocus>

                                @error('$type_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>
                        </div>
</div>