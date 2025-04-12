<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Insurance</h3>
        </div>

    </div>

    <form id="kt_account_profile_details_form" class="form" method="POST"
        action="{{ route('dashboard.vehicles.show.insurance.update', $vehicle['id']) }}" enctype="multipart/form-data">
        @csrf
        
        <div class="card-body border-top p-9">

            @include('dashboard.includes.errors.default')

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label fw-semibold fs-6">
                    <span class="required">Insurance</span>
                </label>

                <div class="col-lg-4 fv-row">
                    <select name="unit_type_id" aria-label="Select an Insurance" data-control="select2"
                        data-placeholder="Select an Insurance"
                        class="form-select form-select-solid form-select-lg fw-semibold">
                        <option value="">Select an Insurance</option>

                        @foreach($references['insurance']['items'] as $item)
                            <option 
                                value="{{ $item['id'] }}"
                                {{ $item['id'] == ($vehicle['insurance']['id'] ?? null) ? 'selected' : '' }}
                                >
                                {{ $item['name'] }} / {{ $item['number'] }}
                            </option>
                        @endforeach
                    </select>
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