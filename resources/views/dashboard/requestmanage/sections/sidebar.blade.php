
<div class="card card-flush py-4">
	<div class="card-header">
		<div class="card-title">
			<h2>{{ $request['service']['name'] }}</h2>
		</div>
		<div class="card-toolbar">
			<div class="rounded-circle @if( 1 == 1 ) bg-success @else bg-danger @endif w-15px h-15px">

			</div>
		</div>
	</div>

	<form action="{{ route('dashboard.requestmanage.updatestatus', $request['id']) }}" method="POST">
		@csrf

		<div class="card-body pt-0">

			<select class="form-select mb-2" data-control="select2" data-hide-search="true"
				data-placeholder="Select an option" id="kt_ecommerce_add_product_status_select" name="status_id">

				@foreach( $references['requestStatus']['items'] as $item )
					<option 
						value="{{ $item['id'] }}" 
						@if( $request['status']['id'] == $item['id'] ) selected="selected" @endif 
						>
						{{ $item['name'] }}
					</option>
				@endforeach
				
			</select>

		</div>

		<div class="d-flex justify-content-center mt-30">
			<button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
				<span class="indicator-label">Save Status</span>
			</button>
		</div>

	</form>

</div>

