<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Inspections</h3>
        </div>

        <div class="card-body pt-0">


                <div class="mb-10 fv-row fv-plugins-icon-container">
                    <label class="required form-label">{{ $field['title'] }}</label>
                    <input type="text" name="fields[{{ $field['slug'] }}]" class="form-control mb-2" placeholder=""
                        value="{{ $field['value'] }}">
                    <div class="text-muted fs-7">

                    </div>
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                    </div>
                </div>


        </div>

    </div>

</div>