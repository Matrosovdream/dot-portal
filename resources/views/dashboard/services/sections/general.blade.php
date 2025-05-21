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

                <!-- Group -->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Group</label>
                    <select name="group_id" class="form-select form-select-solid" data-control="select2"
                            data-placeholder="Select a group">
                        <option></option>
                        @foreach ($references['serviceGroups']['items'] as $group)
                            <option value="{{ $group['id'] }}"
                                    {{ $service['group']['id'] == $group['id'] ? 'selected' : '' }}>
                                {{ $group['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Name -->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Name</label>
                    <input type="text" name="name" value="{{ $service['name'] }}"
                           class="form-control form-control-solid" placeholder="" />
                </div>

                <!-- Slug -->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Slug</label>
                    <input type="text" name="slug" value="{{ $service['slug'] }}"
                           class="form-control form-control-solid" placeholder="" />
                </div>

                <!-- Description -->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Description</label>
                    <textarea name="description" class="form-control form-control-solid"
                              placeholder="">{{ $service['description'] }}</textarea>
                </div>

                <!-- Paid Service -->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Paid Service</label>
                    <select name="is_paid" class="form-select form-select-solid" data-control="select2"
                            data-placeholder="Select option" id="is_paid_select">
                        <option value="0" {{ !$service['is_paid'] ? 'selected' : '' }}>No</option>
                        <option value="1" {{ $service['is_paid'] ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>

                <!-- Price -->
                <div class="fv-row mb-7" id="price_block">
                    <label class="fs-6 fw-semibold mb-2 required">Price</label>
                    <input type="text" name="price" value="{{ $service['price'] }}"
                           class="form-control form-control-solid" placeholder="" />
                </div>

                <!-- Form Type -->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Form Type</label>
                    <select name="form_type" class="form-select form-select-solid" data-control="select2"
                            data-placeholder="Select form type" id="form_type_select">
                        <option value="custom" {{ $service['form_type'] === 'custom' ? 'selected' : '' }}>Custom</option>
                        <option value="predefined" {{ $service['form_type'] === 'predefined' ? 'selected' : '' }}>Predefined</option>
                    </select>
                </div>

                <!-- Predefined Forms -->
                <div class="fv-row mb-7" id="predefined_forms_block">
                    <label class="fs-6 fw-semibold mb-2 required">Predefined Form</label>
                    <select name="predefined_form_id" class="form-select form-select-solid" data-control="select2"
                            data-placeholder="Select predefined form">
                        <option></option>
                        @foreach ($predefinedForms as $form)
                            <option value="{{ $form['id'] }}"
                                    {{ $service['predefined_form_id'] == $form['id'] ? 'selected' : '' }}>
                                {{ $form['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit -->
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

<!-- JS Logic -->
<script>
    function togglePriceBlock() {
        const isPaid = document.getElementById('is_paid_select').value;
        const priceBlock = document.getElementById('price_block');
        priceBlock.style.display = (isPaid === '1') ? 'block' : 'none';
    }

    function togglePredefinedForms() {
        const formType = document.getElementById('form_type_select').value;
        const predefinedBlock = document.getElementById('predefined_forms_block');
        predefinedBlock.style.display = (formType === 'predefined') ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        togglePriceBlock();
        togglePredefinedForms();

        document.getElementById('is_paid_select').addEventListener('change', togglePriceBlock);
        document.getElementById('form_type_select').addEventListener('change', togglePredefinedForms);
    });
</script>
