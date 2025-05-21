
<div class="card card-flush py-4">
			<div class="card-header">
				<div class="card-title">
					<h2>{{ $service['name'] }}</h2>
				</div>
				<div class="card-toolbar">
					<div class="rounded-circle @if( 1 == 1 ) bg-success @else bg-danger @endif w-15px h-15px" id="kt_ecommerce_add_product_status"></div>
				</div>
			</div>

			<form method="POST" action="{{ route('dashboard.services.updatestatus', $service['id']) }}" enctype="multipart/form-data">
				@csrf

				<div class="card-body pt-0">

					<select class="form-select mb-2" data-control="select2" data-hide-search="true"
						data-placeholder="Select an option" id="kt_ecommerce_add_product_status_select" name="status">
						<option value="1" @if( $service['status_id'] == 1 ) selected="selected" @endif >Published</option>
						<option value="2" @if( $service['status_id'] == 2 ) selected="selected" @endif>Draft</option>
					</select>

				</div>

				<div class="d-flex justify-content-center mt-30">
					<button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
						<span class="indicator-label">Save Changes</span>
					</button>
				</div>

			</form>

		</div>

