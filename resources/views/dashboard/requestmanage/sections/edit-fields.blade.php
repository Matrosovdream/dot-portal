<div class="tab-pane fade" id="kt_ecommerce_customer_edit_fields" role="tabpanel">
<div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
    <div class="card-header cursor-pointer">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Edit Request Details</h3>
        </div>

    </div>

    <div class="card-body p-9">

        <form action="{{ route('dashboard.requestmanage.updatefields', ['request_id' => $request_id]) }}"
            class="form mb-15 fv-plugins-bootstrap5 fv-plugins-framework" method="POST">
            @csrf
        
            @include('dashboard.includes.errors.default')
        
            <div class="row mb-5">
        
                @foreach($fieldValues['items'] as $data)
        
                    @include('dashboard.servicerequest.includes.form-field', ['field' => $data])
        
                @endforeach
        
            </div>
        
            <div class="separator mb-8"></div>
        
            <button type="submit" class="btn btn-primary" id="kt_careers_submit_button">
                <span class="indicator-label">Apply changes</span>
            </button>
        
        </form>

    </div>
</div>

</div>

