<div class="tab-pane fade active show" id="kt_ecommerce_customer_general" role="tabpanel">
    <div class="card pt-4 mb-6 mb-xl-9">

        <div class="card-header border-0">
            <div class="card-title">
                <h2>Settings</h2>
            </div>
        </div>


        <div class="card-body pt-0 pb-5">

            <form class="form" method="POST" action="{{ route('dashboard.services.update', $service['id']) }}"
                id="kt_ecommerce_customer_profile">
                @csrf

                @include('dashboard.includes.errors.default')

                <!-- Groups -->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Group</label>
                    <select name="group_id" class="form-select form-select-solid" data-control="select2"
                        data-placeholder="Select a group">
                        <option></option>
                        @foreach ($references['serviceGroups']['items'] as $group)
                            <option 
                                value="{{ $group['id'] }}"
                                {{ $service['group']['id'] == $group['id'] ? 'selected' : '' }}>
                                {{ $group['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Name</label>
                    <input type="text" name="name" value="{{ $service['name'] }}"
                        class="form-control form-control-solid" placeholder="" />
                </div>
                
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Slug</label>
                    <input type="text" name="slug" value="{{ $service['slug'] }}"
                        class="form-control form-control-solid" placeholder="" />
                </div>

                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Description</label>
                    <textarea name="description" class="form-control form-control-solid"
                        placeholder="">{{ $service['description'] }}</textarea>
                </div>

                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Price</label>
                    <input type="text" name="price" value="{{ $service['price'] }}"
                        class="form-control form-control-solid" placeholder="" />
                </div>

                <div class="d-flex justify-content-end">

                    <button type="submit" id="kt_ecommerce_customer_profile_submit"
                        class="btn btn-light-primary">
                        <span class="indicator-label">Save</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>

                </div>
            </form>

        </div>

    </div>

</div>


<div class="tab-pane fade" id="kt_ecommerce_customer_advanced" role="tabpanel">
    <div class="card pt-4 mb-6 mb-xl-9">

        <div class="card-header border-0">

            <div class="card-title">
                <h2>Security Details</h2>
            </div>

        </div>


        <div class="card-body pt-0 pb-5">

            <form class="form" method="POST" action="{{ route('dashboard.users.update', $service['id']) }}"
                id="kt_ecommerce_customer_profile">
                @csrf

                <input type="hidden" name="action" value="save_password" />

                <div class="row row-cols-1 row-cols-md-2">

                    <div class="col">
                        <div class="fv-row mb-7">

                            <label class="fs-6 fw-semibold mb-2">
                                <span class="required">Password</span>
                            </label>

                            <input type="password" class="form-control form-control-solid" placeholder=""
                                name="password" value="" />

                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end">

                    <button type="submit" id="kt_ecommerce_customer_profile_submit"
                        class="btn btn-light-primary">
                        <span class="indicator-label">Save</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>

                </div>
            </form>

        </div>

    </div>
</div>
</div>