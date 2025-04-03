<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Address</h3>
        </div>

    </div>

    <form id="kt_account_profile_details_form" class="form" method="POST"
        action="{{ route('dashboard.drivers.show.address.update', $driver['id']) }}" enctype="multipart/form-data">

        @csrf

        <div class="card-body border-top p-9">

            @include('dashboard.includes.errors.default')

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Address 1
                </label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="address1" class="form-control form-control-lg form-control-solid"
                        placeholder="Address 1" value="{{ $driver['address']['address1'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Address 2
                </label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="address2" class="form-control form-control-lg form-control-solid"
                        placeholder="Address 2" value="{{ $driver['address']['address2'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    City
                </label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="city" class="form-control form-control-lg form-control-solid"
                        placeholder="City" value="{{ $driver['address']['city'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    Zip
                </label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="zip" class="form-control form-control-lg form-control-solid"
                        placeholder="zip" value="{{ $driver['address']['zip'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">
                    State
                </label>

                <div class="col-lg-4 fv-row">
                    <select name="state_id" class="form-select form-select-lg form-select-solid" data-control="select2"
                        data-placeholder="Select..." data-hide-search="true">
                        <option></option>
                        @foreach($references['state']['items'] as $state)
                            <option 
                                value="{{ $state['id'] }}"
                                {{ ($driver['address']['state_id'] ?? '') == $state['id'] ? 'selected' : '' }}
                                >
                                {{ $state['name'] }}
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