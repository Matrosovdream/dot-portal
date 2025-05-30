<div class="tab-pane fade" id="kt_ecommerce_customer_fields" role="tabpanel">
    <div class="card card-flush py-4">
        <div class="card-header">
            <div class="card-title">
                <h2>Custom fields</h2>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="" data-kt-ecommerce-catalog-add-product="auto-options">

                <div id="kt_ecommerce_add_product_options">

                    @include('dashboard.services.sections.form-fields', ['fields' => $service['formFields']['items']])

                    <div class="form-group mt-5">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#kt_modal_product_form_field_general"
                            class="btn btn-sm btn-light-primary">
                            <i class="ki-duotone ki-plus fs-2"></i>Add another field</button>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('.fields-modal form');

        form.addEventListener('submit', function (e) {
            let isValid = true;

            // Validate Field select
            const fieldSelect = form.querySelector('select[name="field_id"]');
            if (!fieldSelect.value || fieldSelect.value === 'Select field') {
                fieldSelect.classList.add('is-invalid');
                isValid = false;
            } else {
                fieldSelect.classList.remove('is-invalid');
            }

            // Validate Required select
            const requiredSelect = form.querySelector('select[name="required"]');
            if (requiredSelect.value !== "0" && requiredSelect.value !== "1") {
                requiredSelect.classList.add('is-invalid');
                isValid = false;
            } else {
                requiredSelect.classList.remove('is-invalid');
            }

            if (!isValid) {
                e.preventDefault(); // Stop form submission
            }
        });
    });
</script>


<style>
    .is-invalid {
        border-color: #dc3545;
    }
</style>
