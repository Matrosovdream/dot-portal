<form 
    action="{{ route('dashboard.servicerequest.store.request', ['group' => $group['slug'], 'service' => $service['slug']]) }}"
    class="form mb-15 fv-plugins-bootstrap5 fv-plugins-framework" 
    method="POST" 
    enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="form_type" value="predefined">

    @include('dashboard.includes.errors.default')

    <div class="row mb-5">

        @include( $formPath )

    </div>

    <div class="separator mb-8"></div>

    <button type="submit" class="btn btn-primary" id="kt_careers_submit_button">

        <span class="indicator-label">Apply Now</span>

        <span class="indicator-progress">Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>

    </button>

</form>



