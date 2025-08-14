<div class="card mb-5 mb-xl-10">

    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">

        <div class="card-title m-0">
            <h3 class="fw-bold m-0">General</h3>
        </div>

    </div>

    <form id="kt_account_profile_details_form" class="form" method="POST"
        action="{{ route('dashboard.usersubscriptions.show.user.store', $sub['id']) }}" enctype="multipart/form-data">

        @csrf

        <input type="hidden" name="action" value="update_general" />

        <div class="card-body border-top p-9">

            @include('dashboard.includes.errors.default')

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Status</label>

                <div class="col-lg-4 fv-row">
                    <select name="status" class="form-select form-select-solid form-select-lg">
                        <option value="active" {{ $sub['status'] == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $sub['status'] == 'disabled' ? 'selected' : '' }}>Disabled</option>
                    </select>
                </div>
            </div>

            <div class="row mb-6">
                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Subscription</label>

                <div class="col-lg-4 fv-row">
                    <select name="subscription_id" class="form-select form-select-solid form-select-lg">
                        <option value="">Select Subscription</option>
                        @foreach($subList['items'] as $subItem)
                            <option value="{{ $subItem['id'] }}" 
                                {{ $subItem['id'] == $sub['subscription_id'] ? 'selected' : '' }}>
                                {{ $subItem['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Drivers number</label>

                <div class="col-lg-4 fv-row">
                    <input type="number" name="drivers_number" class="form-control form-control-lg form-control-solid"
                        placeholder="" value="{{ $sub['drivers_number'] ?? '' }}" />
                </div>

            </div>

            <div class="row mb-6">

                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Price per driver</label>

                <div class="col-lg-4 fv-row">
                    <input type="number" name="price_per_driver" class="form-control form-control-lg form-control-solid"
                        placeholder="" value="{{ $sub['price_per_driver'] ?? '' }}" />
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