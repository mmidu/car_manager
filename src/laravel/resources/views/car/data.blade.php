                        <div class="car">
                        <div class="card-header" style="grid-area: t_a">AUTO</div>

                        <div class="form-group row" style="grid-area: a">
                            <span class="col-md-4 three_col">
                                <label for="model" class="col-md-4 col-form-label text-md-right">{{ __('Modello') }}</label>
                                <input id="model" type="text" class="form-control @error('model') is-invalid @enderror" name="model" value="{{ $car->model ?? null }}" required autocomplete="model" autofocus>

                                @error('model')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>

        

                            <span class="col-md-4 three_col">
                                <label for="manufacturer" class="col-md-4 col-form-label text-md-right">{{ __('Marca') }}</label>
                                <input id="manufacturer" type="text" class="form-control @error('manufacturer') is-invalid @enderror" name="manufacturer" value="{{ $car->manufacturer ?? null }}" required autocomplete="manufacturer" autofocus>

                                @error('manufacturer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>

                            

                            <span class="col-md-4 three_col">
                                <label for="year" class="col-md-4 col-form-label text-md-right">{{ __('Anno') }}</label>
                                <input id="year" type="date" class="form-control @error('year') is-invalid @enderror" name="year" value="{{$car->year ? $car->year->format('Y-m-d') : null}}" max="{{ now()->format('Y-m-d')}}" required autocomplete="year" autofocus>

                                @error('year')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>
                        </div>

                        <div class="form-group row" style="grid-area: b">
                            <span class="col-md-4 three_col">
                                <label for="plate" class="col-md-4 col-form-label text-md-right">{{ __('Targa') }}</label>
                                <input id="plate" type="text" class="form-control @error('plate') is-invalid @enderror" value="{{ $car->plate ?? null }}" required readonly>

                                @error('plate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>

        

                            <span class="col-md-4 three_col">
                                <label for="engine_displacement" class="col-md-4 col-form-label text-md-right">{{ __('Cilindrata') }}</label>
                                <input id="engine_displacement" type="number" class="form-control @error('engine_displacement') is-invalid @enderror" name="engine_displacement" value="{{ $car->engine_displacement ?? null }}" required autocomplete="engine_displacement" autofocus>

                                @error('engine_displacement')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>

                            

                            <span class="col-md-4 three_col">
                                <label for="hp" class="col-md-4 col-form-label text-md-right">{{ __('Cavalli') }}</label>
                                <input id="hp" type="number" class="form-control @error('hp') is-invalid @enderror" name="hp" value="{{ $car->hp ?? null }}" required autocomplete="hp" autofocus>

                                @error('hp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </span>
                        </div>
                    </div>