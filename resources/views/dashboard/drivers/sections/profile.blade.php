<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">General</h3>
        </div>

    </div>

    <form id="kt_account_profile_details_form" class="form" method="POST"
        action="{{ route('dashboard.drivers.show.profile.update', $driver['id']) }}" enctype="multipart/form-data">

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
                            <option value="{{ $item['id'] }}" {{ $item['id'] == $driver['driver_type_id'] ? 'selected' : '' }}>
                                {{ $item['title'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>

                <div class="col-lg-8">
                    <div class="row">

                        <div class="col-lg-6 fv-row">
                            <input type="text" name="firstname"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="First name" value="{{ $driver['firstname'] }}" />
                        </div>

                        <div class="col-lg-6 fv-row">
                            <input type="text" name="middlename" class="form-control form-control-lg form-control-solid"
                                placeholder="Middle name" value="{{ $driver['middlename'] }}" />
                        </div>

                    </div>
                </div>
            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label fw-semibold fs-6"></label>

                <div class="col-lg-8">
                    <div class="row">

                        <div class="col-lg-6 fv-row">
                            <input type="text" name="lastname"
                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                placeholder="Last name" value="{{ $driver['lastname'] }}" />
                        </div>

                    </div>
                </div>
            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Phone</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="phone" class="form-control form-control-lg form-control-solid"
                        placeholder="Phone" value="{{ $driver['phone'] }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Email</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="email" class="form-control form-control-lg form-control-solid"
                        placeholder="Email" value="{{ $driver['email'] }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">DOB</label>

                <div class="col-lg-4 fv-row">
                    <input type="date" name="dob" class="form-control form-control-lg form-control-solid"
                        value="{{ $driver['dob'] }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">SSN</label>

                <div class="col-lg-4 fv-row">
                    <input type="text" name="ssn" class="form-control form-control-lg form-control-solid"
                        placeholder="SSN" value="{{ $driver['ssn'] }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Hire date</label>

                <div class="col-lg-4 fv-row">
                    <input type="date" name="hire_date" class="form-control form-control-lg form-control-solid"
                        value="{{ $driver['hire_date'] }}" />
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