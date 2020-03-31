@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header">{{ __('Register') }}</div>

				<div class="card-body">
					<form method="POST" action="{{ route('register') }}">
						@csrf

						<div class="user form_group">
							<div class="form_row">
								<div>
									<label class="form_el_title" for="first_name">{{ __('Nome') }}</label>
									<input class="form_el_input" placeholder="Nome" type="text" name="first_name" id="first_name" required autocomplete="first_name">
								</div>

								<div>
									<label class="form_el_title" for="last_name">{{ __('Cognome') }}</label>
									<input class="form_el_input" placeholder="Cognome" type="text" name="last_name" id="last_name" autocomplete="last_name">
								</div>

								<div>
									<label class="form_el_title" for="birth_date">{{ __('Data di nascita') }}</label>
									<input class="form_el_input" type="date" name="birth_date" id="birth_date" max="{{ now()->format('Y-m-d')}}" required autocomplete="birth_date" placeholder="YYYY-MM-DD" required pattern="\d{4}-\d{2}-\d{2}">
								</div>
							</div>

							<div class="form_row">
								<div>
									<label class="form_el_title" for="fiscal_code">{{ __('Codice fiscale') }}</label>
									<input class="form_el_input upper" placeholder="Codice fiscale" type="text" name="fiscal_code" id="fiscal_code" required>
								</div>
								
								<div>
									<label class="form_el_title" for="address">{{ __('Indirizzo') }}</label>
									<input class="form_el_input" placeholder="Indirizzo" type="text" name="address" id="address" required autocomplete="address">
								</div>
								
								<div>
								   
									<label class="form_el_title" for="gender">{{ __('Genere') }}</label>
									<select class="form_el_input" name="gender" id="gender" required>
										<option>Seleziona</option>
										<option value="40">F</option>
										<option value="0">M</option>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group row mb-0">
							<div class="col-md-6 offset-md-4">
								<button id="submit" type="submit" class="btn btn-primary" disabled>
									{{ __('Register') }}
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

$(document).on('focusout', 'input, select', function(e){
	let form_data = {}
	$('input, select').each(function(){
		form_data[$(this).attr('name')] = $(this).val()
	})

	$.ajax({
	 	headers: {
	 		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	 	},
	 	url: "{{route('transfer_validate')}}",
	 	data: form_data,
	 	type: 'POST',
	 	dataType: 'json',
	 	success: function(data){
	 		if(data){
	 			$('#fiscal_code').removeClass('error')
	 		} else {
	 			$('#fiscal_code').addClass('')
	 		}
	 		$('#submit').prop('disabled', !data);
	 	}
	})
})
</script>
@endsection
