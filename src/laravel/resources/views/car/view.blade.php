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
                        
                            <div>
                                <button class="btn btn-primary old_owner" id="transfer">
                                    {{ __('Trasferisci proprietà') }}
                                </button>
                            </div>

                            <div">
                                <button type="submit" class="btn btn-primary hide new_owner">
                                    {{ __('Conferma') }}
                                </button>
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
        $('.car, .old_owner').addClass('hide')
        $('.new_owner').removeClass('hide')
    });

</script>
@endsection

