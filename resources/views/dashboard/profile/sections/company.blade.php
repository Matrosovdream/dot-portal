<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">General</h3>
        </div>

    </div>
    
    <form id="kt_account_profile_details_form" class="form" method="POST"
        action="{{ route('dashboard.company.update', $company['id']) }}" enctype="multipart/form-data">
        @csrf

        @if($errors->any())

            <div class="alert alert-danger p-9">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        @endif

        <div class="card-body border-top p-9">

            @php /*
            <div class="row mb-6">

                <label class="col-lg-4 col-form-label fw-semibold fs-6">
                    <span class="required">Driver type</span>
                </label>

                <div class="col-lg-4 fv-row">
                    <select name="driver_type_id" aria-label="Select a Driver type" data-control="select2"
                        data-placeholder="Select a Driver type"
                        class="form-select form-select-solid form-select-lg fw-semibold">
                        <option value="">Select a Driver type</option>

                        @foreach($references['driverType']['items'] as $item)
                            <option value="{{ $item['id'] }}" {{ $item['id'] == $company['driver_type_id'] ? 'selected' : '' }}>
                                {{ $item['title'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
            */ @endphp

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Name</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="name" class="form-control form-control-lg form-control-solid"
                        placeholder="name" value="{{ $company['name'] }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Phone</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="phone" class="form-control form-control-lg form-control-solid"
                        placeholder="Phone" value="{{ $company['phone'] }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">DOT number</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="dot_number" class="form-control form-control-lg form-control-solid"
                        value="{{ $company['dot_number'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">MC number</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="mc_number" class="form-control form-control-lg form-control-solid"
                        value="{{ $company['mc_number'] ?? '' }}" />
                </div>

            </div>

        </div>

        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
            <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save
                Changes</button>
        </div>

    </form>

</div>