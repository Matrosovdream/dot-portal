<div class="modal fade" id="kt_modal_new_card" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">

            <div class="modal-header">

                <h2>Add New Card</h2>

                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>

            </div>

            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">

                <form id="kt_modal_new_card_form" class="form" method="POST" action="{{ route('dashboard.subscription.cards.store') }}">
                    @csrf

                    <div class="d-flex flex-column mb-7 fv-row">

                        <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                            <span class="required">Name On Card</span>
                            <span class="ms-1" data-bs-toggle="tooltip" title="Specify a card holder's name">
                                <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                        </label>

                        <input type="text" class="form-control form-control-solid" placeholder="Max Doe" name="card_name"
                        value="Max Doe"
                        />
                    </div>

                    <!-- New First and Last Name block -->
                    <div class="row mb-7 fv-row">
                        <div class="col-md-6">
                            <label class="required fs-6 fw-semibold form-label mb-2">First Name</label>
                            <input type="text" class="form-control form-control-solid" placeholder="First Name" name="first_name" value="Max" />
                        </div>

                        <div class="col-md-6">
                            <label class="required fs-6 fw-semibold form-label mb-2">Last Name</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Last Name" name="last_name" value="Doe" />
                        </div>
                    </div>


                    <div class="d-flex flex-column mb-7 fv-row">

                        <label class="required fs-6 fw-semibold form-label mb-2">Card Number</label>

                        <div class="position-relative">

                            <input type="text" class="form-control form-control-solid" placeholder="Enter card number"
                                name="card_number" value="4111 1111 1111 1111" />

                            <div class="position-absolute translate-middle-y top-50 end-0 me-5">
                                <img src="assets/media/svg/card-logos/visa.svg" alt="" class="h-25px" />
                                <img src="assets/media/svg/card-logos/mastercard.svg" alt="" class="h-25px" />
                                <img src="assets/media/svg/card-logos/american-express.svg" alt="" class="h-25px" />
                            </div>

                        </div>

                    </div>

                    <div class="row mb-10">

                        <div class="col-md-8 fv-row">

                            <label class="required fs-6 fw-semibold form-label mb-2">Expiration Date</label>

                            <div class="row fv-row">

                                <div class="col-6">
                                    <select name="card_expiry_month" class="form-select form-select-solid"
                                        data-control="select2" data-hide-search="true" data-placeholder="Month">
                                        <option></option>
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3" selected>03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                </div>

                                <div class="col-6">
                                    <select name="card_expiry_year" class="form-select form-select-solid"
                                        data-control="select2" data-hide-search="true" data-placeholder="Year">
                                        <option></option>
                                        <option value="2025">2025</option>
                                        <option value="2026">2026</option>
                                        <option value="2027" selected>2027</option>
                                        <option value="2028">2028</option>
                                        <option value="2029">2029</option>
                                        <option value="2030">2030</option>
                                        <option value="2031">2031</option>
                                        <option value="2032">2032</option>
                                        <option value="2033">2033</option>
                                        <option value="2034">2034</option>
                                    </select>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-4 fv-row">

                            <label class="d-flex align-items-center fs-6 fw-semibold form-label mb-2">
                                <span class="required">CVV</span>
                                <span class="ms-1" data-bs-toggle="tooltip" title="Enter a card CVV code">
                                    <i class="ki-duotone ki-information-5 text-gray-500 fs-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                            </label>

                            <div class="position-relative">

                                <input type="text" class="form-control form-control-solid" minlength="3" maxlength="4"
                                    placeholder="CVV" name="card_cvv" value="000" />
    
                                <div class="position-absolute translate-middle-y top-50 end-0 me-3">
                                    <i class="ki-duotone ki-credit-cart fs-2hx">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="d-flex flex-stack">

                        <div class="me-5">
                            <label class="fs-6 fw-semibold form-label">Save Card for further billing?</label>
                            <div class="fs-7 fw-semibold text-muted">If you need more info, please check budget planning
                            </div>
                        </div>

                        <label class="form-check form-switch form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="1" checked="checked" disabled />
                            <span class="form-check-label fw-semibold text-muted">Save Card</span>
                        </label>

                    </div>

                    <div class="text-center pt-15">
                        <button type="reset" id="kt_modal_new_card_cancel" class="btn btn-light me-3">Discard</button>
                        <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('kt_modal_new_card_form');
        const submitBtn = document.getElementById('kt_modal_new_card_submit');
        const modalBody = form.closest('.modal-body');
        const indicatorLabel = submitBtn.querySelector('.indicator-label');
        const originalText = indicatorLabel.textContent;
    
        form.addEventListener('submit', function (e) {
            e.preventDefault();
    
            const formData = new FormData(form);
    
            // Update button state
            submitBtn.disabled = true;
            indicatorLabel.textContent = 'Processing...';
            submitBtn.querySelector('.indicator-progress').classList.remove('d-none');
    
            // Clear existing errors
            const oldError = modalBody.querySelector('.alert.alert-danger');
            if (oldError) oldError.remove();
    
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    showErrors(data.errors || { error: [data.message || 'Unknown error'] });
                }
            })
            .catch(() => {
                showErrors({ error: ['Something went wrong. Please try again.'] });
            })
            .finally(() => {
                submitBtn.disabled = false;
                indicatorLabel.textContent = originalText;
                submitBtn.querySelector('.indicator-progress').classList.add('d-none');
            });
    
            function showErrors(errors) {
                let html = '<div class="alert alert-danger mb-5">';
                for (const key in errors) {
                    html += `<div>${errors[key][0]}</div>`;
                }
                html += '</div>';
                modalBody.insertAdjacentHTML('afterbegin', html);
            }
        });
    });
 </script>
    

