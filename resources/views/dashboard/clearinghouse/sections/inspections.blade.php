<div class="tab-pane" id="kt_ecommerce_customer_fields" role="tabpanel">
    <div class="card card-flush py-4">
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            data-bs-target="#kt_account_profile_details" aria-expanded="true"
            aria-controls="kt_account_profile_details">

            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Inspections</h3>
            </div>

        </div>

        <div class="card-body pt-0">
            <div class="" data-kt-ecommerce-catalog-add-product="auto-options">

                <div id="kt_ecommerce_add_product_options">

                    @include('dashboard.vehicles.partials.inspections-table', ['inspections' => $inspections['items']])

                    <!--
                    <div class="form-group mt-5">
                        <button type="button" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_product_form_field_general" class="btn btn-sm btn-light-primary">
                            <i class="ki-duotone ki-plus fs-2"></i>Add another inspection</button>
                    </div>
                    -->

                </div>

            </div>
        </div>

    </div>
</div>