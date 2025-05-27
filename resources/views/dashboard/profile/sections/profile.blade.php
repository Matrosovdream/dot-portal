<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">General</h3>
        </div>

    </div>

    <form id="kt_account_profile_details_form" class="form" method="POST"
        action="{{ route('dashboard.profile.update', $user['id']) }}" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="entity" value="profile">

        <div class="card-body border-top p-9">

            @include('dashboard.includes.errors.default')

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>

                <div class="col-lg-8">
                    <div class="row">

                        <div class="col-lg-6 fv-row">
                            <input type="text" name="firstname"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="First name" value="{{ $user['firstname'] }}" />
                        </div>

                        <div class="col-lg-6 fv-row">
                            <input type="text" name="lastname" class="form-control form-control-lg form-control-solid"
                                placeholder="Last name" value="{{ $user['lastname'] }}" />
                        </div>

                    </div>
                </div>
            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Phone</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="phone" class="form-control form-control-lg form-control-solid"
                        placeholder="Phone" value="{{ $user['phone'] }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">DOB</label>

                <div class="col-lg-4 fv-row">
                    <input type="date" name="birthday" class="form-control form-control-lg form-control-solid datepicker"
                        value="{{ $user['birthday'] ?? '' }}" />
                </div>

            </div>

        </div>

        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save
                Changes</button>
        </div>

    </form>

</div>


<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Address</h3>
        </div>

    </div>

    <form id="kt_account_profile_details_form" class="form" method="POST"
        action="{{ route('dashboard.profile.address.update') }}" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="entity" value="address">

        @include('dashboard.includes.errors.default')

        <div class="card-body border-top p-9">

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Address 1</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="address1" class="form-control form-control-lg form-control-solid"
                        placeholder="Address 2" value="{{ $user['address']['address1'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Address 2</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="address2" class="form-control form-control-lg form-control-solid"
                        placeholder="Address 2" value="{{ $user['address']['address2'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">City</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="city" class="form-control form-control-lg form-control-solid"
                        placeholder="City" value="{{ $user['address']['city'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label fw-semibold fs-6">
                    <span class="required">State</span>
                </label>

                <div class="col-lg-4 fv-row">
                    <select name="state_id" aria-label="" data-control="select2"
                        data-placeholder="Select state"
                        class="form-select form-select-solid form-select-lg fw-semibold">
                        <option value="">Select state</option>

                        @foreach($references['state']['items'] as $item)
                            <option 
                                value="{{ $item['id'] }}"
                                {{ $item['id'] == ($user['address']['state']['id'] ?? '') ? 'selected' : '' }}
                                >
                                {{ $item['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

        </div>

        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save
                Changes</button>
        </div>

    </form>

</div>