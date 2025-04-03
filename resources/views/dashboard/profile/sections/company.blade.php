<div class="card mb-5 mb-xl-10">

    <form id="kt_account_profile_details_form" class="form" method="POST"
        action="{{ route('dashboard.profile.company.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            data-bs-target="#kt_account_profile_details" aria-expanded="true"
            aria-controls="kt_account_profile_details">

            <div class="card-title m-0">
                <h3 class="fw-bold m-0">General</h3>
            </div>

        </div>

        <div class="card-body border-top p-9">

            @include('dashboard.includes.errors.default')

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Name</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="name" class="form-control form-control-lg form-control-solid"
                        placeholder="name" value="{{ $company['name'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Phone</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="phone" class="form-control form-control-lg form-control-solid"
                        placeholder="Phone" value="{{ $company['phone'] ?? '' }}" />
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



        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            data-bs-target="#kt_account_profile_details" aria-expanded="true"
            aria-controls="kt_account_profile_details">

            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Business Address</h3>
            </div>

        </div>

        <div class="card-body border-top p-9">

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Address 1</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="business[address1]" class="form-control form-control-lg form-control-solid"
                        placeholder="Address 2" value="{{ $user['company']['addresses']['business']['address1'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Address 2</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="business[address2]" class="form-control form-control-lg form-control-solid"
                        placeholder="Address 2" value="{{ $user['company']['addresses']['business']['address2'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">City</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="business[city]" class="form-control form-control-lg form-control-solid"
                        placeholder="City" value="{{ $user['company']['addresses']['business']['city'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label fw-semibold fs-6">
                    <span class="required">State</span>
                </label>

                <div class="col-lg-4 fv-row">
                    <select name="business[state_id]" aria-label="" data-control="select2"
                        data-placeholder="Select state"
                        class="form-select form-select-solid form-select-lg fw-semibold">
                        <option value="">Select state</option>

                        @foreach($references['state']['items'] as $item)
                            <option value="{{ $item['id'] }}" {{ $item['id'] == ($user['company']['addresses']['business']['state_id'] ?? '') ? 'selected' : '' }}>
                                {{ $item['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Zip code</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="business[zip]" class="form-control form-control-lg form-control-solid"
                        placeholder="Zip code" value="{{ $user['company']['addresses']['business']['zip'] ?? '' }}" />
                </div>

            </div>

        </div>



        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            data-bs-target="#kt_account_profile_details" aria-expanded="true"
            aria-controls="kt_account_profile_details">

            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Mailing Address</h3>
            </div>

        </div>

        <div class="card-body border-top p-9">

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Address 1</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="mailing[address1]" class="form-control form-control-lg form-control-solid"
                        placeholder="Address 2" value="{{ $user['company']['addresses']['mailing']['address1'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Address 2</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="mailing[address2]" class="form-control form-control-lg form-control-solid"
                        placeholder="Address 2" value="{{ $user['company']['addresses']['mailing']['address2'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">City</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="mailing[city]" class="form-control form-control-lg form-control-solid"
                        placeholder="City" value="{{ $user['company']['addresses']['mailing']['city'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label fw-semibold fs-6">
                    <span class="required">State</span>
                </label>

                <div class="col-lg-4 fv-row">
                    <select name="mailing[state_id]" aria-label="" data-control="select2"
                        data-placeholder="Select state"
                        class="form-select form-select-solid form-select-lg fw-semibold">
                        <option value="">Select state</option>

                        @foreach($references['state']['items'] as $item)
                            <option value="{{ $item['id'] }}" {{ $item['id'] == ($user['company']['addresses']['mailing']['state_id'] ?? '') ? 'selected' : '' }}>
                                {{ $item['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Zip code</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="mailing[zip]" class="form-control form-control-lg form-control-solid"
                        placeholder="Zip code" value="{{ $user['company']['addresses']['mailing']['zip'] ?? '' }}" />
                </div>

            </div>

        </div>

        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">
                Save Changes
            </button>
        </div>

    </form>

</div>