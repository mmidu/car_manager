@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Search car</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('car_search') }}">
                        @csrf

                        <div class="row">

                            <div>
                                <label for="plate" class="col-md-4 col-form-label text-md-right">{{ __('Plate') }}</label>

                                <div class="col-md-6">
                                    <input id="plate" type="text" class="form-control upper @error('plate') is-invalid @enderror" name="plate" value="{{ old('plate') }}" required autocomplete="plate" autofocus>
                                </div>
                                @error('error')
                                    <div class="invalid-feedback" role="alert">
                                         <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Search') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
