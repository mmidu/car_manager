@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" class="ct_form" action="{{ route('car_transfer') }}">
                        @csrf
                        @include('car.data')
                        @include('user.data', ['old_owner' => true])
                        @include('user.data', ['old_owner' => false])
                        
                        <div class="form-group row mb-0 e">
                            <div class="col-md-6 offset-md-4 b">
                                <button class="btn btn-primary old_owner" id="transfer">
                                    {{ __('Trasferisci proprietà') }}
                                </button>
                            </div>
                        </div>

                        <div class="form-group row mb-0 e">
                            <div class="col-md-6 offset-md-4 b">
                                <button type="submit" class="btn btn-primary hide new_owner">
                                    {{ __('Conferma') }}
                                </button>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).on("click", '#transfer', function(e){
        e.preventDefault()
        $('.car, .old_owner').hide()
        $('.new_owner').show()
    });

</script>
@endsection

