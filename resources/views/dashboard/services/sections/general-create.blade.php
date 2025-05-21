<div class="tab-pane fade active show" id="kt_ecommerce_customer_general" role="tabpanel">
    <div class="card pt-4 mb-6 mb-xl-9">

        <div class="card-header border-0">
            <div class="card-title">
                <h2>Settings</h2>
            </div>
        </div>

        <div class="card-body pt-0 pb-5">

            <form class="form" method="POST" action="{{ route('dashboard.services.create') }}"
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
                                    {{ old('group_id') == $group['id'] ? 'selected' : '' }}>
                                {{ $group['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Name -->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control form-control-solid" placeholder="" />
                </div>

                <!-- Slug -->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           class="form-control form-control-solid" placeholder="" />
                </div>

                <!-- Description -->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Description</label>
                    <textarea name="description" class="form-control form-control-solid"
                              placeholder="">{{ old('description') }}</textarea>
                </div>

                <!-- Paid Service -->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Paid Service</label>
                    <select name="is_paid" class="form-select form-select-solid" data-control="select2"
                            data-placeholder="Select option" id="is_paid_select">
                        <option value="0" {{ !old('is_paid') ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('is_paid') ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>

                <!-- Price -->
                <div class="fv-row mb-7" id="price_block">
                    <label class="fs-6 fw-semibold mb-2 required">Price</label>
                    <input type="text" name="price" value="{{ old('price') }}"
                           class="form-control form-control-solid" placeholder="" />
                </div>

                <!-- Form Type -->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2 required">Form Type</label>
                    <select name="form_type" class="form-select form-select-solid" data-control="select2"
                            data-placeholder="Select form type" id="form_type_select">
                        <option value="custom" {{ old('form_type') === 'custom' ? 'selected' : '' }}>Custom</option>
                        <option value="predefined" {{ old('form_type') === 'predefined' ? 'selected' : '' }}>Predefined</option>
                    </select>
                </div>

                <!-- Predefined Forms -->
                <div class="fv-row mb-7" id="predefined_forms_block">
                    <label class="fs-6 fw-semibold mb-2 required">Predefined Form</label>
                    <select name="form_id" class="form-select form-select-solid" data-control="select2"
                            data-placeholder="Select predefined form">
                        <option value=""></option>
                        @foreach ($predefinedForms as $form)
                            <option value="{{ $form['id'] }}"
                                    {{ old('form_id') == $form['id'] ? 'selected' : '' }}>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- JS Logic -->
<script>
    function togglePriceBlock() { 
        const isPaid = $('#is_paid_select').val();
        $('#price_block').toggle(isPaid === '1');
    }

    function togglePredefinedForms() {
        const formType = $('#form_type_select').val();
        $('#predefined_forms_block').toggle(formType === 'predefined');
    }

    $(document).ready(function () {
        // Initialize Select2 (in case it's not already auto-initialized)
        $('[data-control="select2"]').select2();

        // Initial display state
        togglePriceBlock();
        togglePredefinedForms();

        // Select2 events
        $('#is_paid_select').on('select2:select', togglePriceBlock);
        $('#form_type_select').on('select2:select', togglePredefinedForms);
    });
</script>


