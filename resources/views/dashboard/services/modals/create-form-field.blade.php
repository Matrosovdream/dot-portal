<div class="modal fade" id="kt_modal_product_form_field_general" tabindex="-1" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Create new field</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>

            <div class="modal-body py-lg-10 px-lg-10">
                <form method="POST"
                    action="{{ route('dashboard.services.fields.store', ['service' => $service['id']]) }}">
                    @csrf

                    <input type="hidden" name="required" value="0" />
                    <input type="hidden" name="entity" value="general" />

                    <div class="row mb-5">

                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <label class="required form-label">Field</label>
                            <select name="field_id" class="form-select form-select-solid">
                                <option>Select field</option>
                                @foreach($formFields as $field)
                                    <option value="{{ $field['id'] }}">{{ $field['title'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <label class="form-label">Required</label>
                            <select name="required" class="form-select form-select-solid mb-2">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>

                    </div>

                    <div class="row mb-5">

                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <label class="form-label">Default value</label>
                            <input type="text" name="default_value" class="form-control form-control-solid"
                                value="{{ old('default_value') }}" />
                        </div>

                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <div class="fv-row fv-plugins-icon-container">
                                <label class="form-label">Placeholder</label>
                                <input type="text" name="placeholder" class="form-control form-control-solid"
                                    value="{{ old('placeholder') }}" />
                            </div>
                        </div>

                    </div>

                    <div class="row mb-5">

                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <label class="form-label">Classes</label>
                            <input type="text" name="classes" class="form-control form-control-solid mb-2" />
                        </div>

                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <div class="mb-10 w-50 fv-row fv-plugins-icon-container">
                                <label class="form-label">Order</label>
                                <input type="number" name="order" value="1"
                                    class="form-control form-control-solid mb-2" />
                            </div>
                        </div>

                    </div>

                    <div class="d-flex flex-stack">

                        <div></div>

                        <div>
                            <button type="submit" class="btn btn-lg btn-primary">
                                Save
                                <i class="ki-duotone ki-arrow-right fs-3 ms-1 me-0">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i></button>
                        </div>

                    </div>

                </form>

            </div>
        </div>

    </div>
</div>