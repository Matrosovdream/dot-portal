<div class="modal fade" id="kt_modal_product_form_field_general" tabindex="-1" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add new inspection</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>

            <div class="modal-body py-lg-10 px-lg-10">
                <form 
                    method="POST"
                    action="{{ route('dashboard.vehicles.show.inspections.store', ['vehicle_id' => $vehicle['id']]) }}"
                    enctype="multipart/form-data"
                    >
                    @csrf

                    @include('dashboard.includes.errors.default')

                    <div class="row mb-5">

                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <label class="form-label required">Inspection date</label>
                            <input 
                                type="date" 
                                name="inspection_date" 
                                class="form-control form-control-solid mb-2" 
                                value="{{ old('inspection_date') }}"
                                />
                        </div>

                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <label class="form-label required">Inspection number</label>
                            <input 
                                type="text" 
                                name="inspection_number" 
                                class="form-control form-control-solid mb-2" 
                                value="{{ old('inspection_number') }}"
                                />
                        </div>

                    </div>

                    <div class="row1 mb-6">

                        <label class="col-lg-6 col-form-label fw-semibold fs-6">
                            Document
                        </label>

                        <div class="col-lg-6 fv-row">
                            <x-file-uploader 
                                :inputName="'document'"
                                :inputId="'document_new'"
                                :value="''"
                                :accept="'image/*,application/pdf'"
                                :multiple="false"
                                :required="false"
                                :label="'Upload file'"
                                :note="'Upload 1 image or PDF'"
                                :description="''"
                            />
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