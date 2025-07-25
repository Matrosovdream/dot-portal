<form 
    action="{{ route('dashboard.servicerequest.store.request', ['group' => $group['slug'], 'service' => $service['slug']]) }}"
    class="form mb-15 fv-plugins-bootstrap5 fv-plugins-framework" 
    method="POST"
    enctype="multipart/form-data"
    >
    @csrf

    <input type="hidden" name="form_type" value="custom">

    @include('dashboard.includes.errors.default')

    <div class="row mb-5">

        @if( $formType == 'custom' )

            @foreach($service['formFields']['items'] as $field)

                @include('dashboard.servicerequest.includes.form-field', ['field' => $field])

            @endforeach

        @elseif( $formType == 'predefined' && isset($formPath) && !empty($formPath) )

            {{-- Predefined Form --}}
            @include( $formPath )

        @endif

    </div>

    <div class="separator mb-8"></div>

    <button type="submit" class="btn btn-primary" id="kt_careers_submit_button">

        <span class="indicator-label">Apply Now</span>

        <span class="indicator-progress">Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>

    </button>

</form>



