@extends('dashboard.layouts.app')

@section('content')

<div class="d-flex flex-column flex-lg-row">
    <!--begin::Content-->
    <div class="flex-lg-row-fluid me-lg-15 order-2 order-lg-1 mb-10 mb-lg-0">
        <!--begin::Form-->
        <form class="form" action="#" id="kt_subscriptions_create_new">
            <!--begin::Customer-->
            <div class="card card-flush pt-3 mb-5 mb-lg-10">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="fw-bold">Customer</h2>
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Description-->
                    <div class="text-gray-500 fw-semibold fs-5 mb-5">Select or add a customer to a subscription:</div>
                    <!--end::Description-->
                    <!--begin::Selected customer-->
                    <div class="d-flex align-items-center p-3 mb-2">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-60px symbol-circle me-3">
                            <img alt="Pic" src="assets/media/avatars/300-5.jpg" />
                        </div>
                        <!--end::Avatar-->
                        <!--begin::Info-->
                        <div class="d-flex flex-column">
                            <!--begin::Name-->
                            <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-2">Sean Bean</a>
                            <!--end::Name-->
                            <!--begin::Email-->
                            <a href="#" class="fw-semibold text-gray-600 text-hover-primary">sean@dellito.com</a>
                            <!--end::Email-->
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Selected customer-->
                    <!--begin::Customer add buttons-->
                    <div class="mb-7 d-none">
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_customer_search">Select Customer</a>
                        <span class="fw-bold text-gray-500 mx-2">or</span>
                        <a href="apps/customers/list.html" class="btn btn-light-primary">Add New Customer</a>
                    </div>
                    <!--end::Customer add buttons-->
                    <!--begin::Customer change button-->
                    <div class="mb-10">
                        <a href="#" class="btn btn-light-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_customer_search">Change Customer</a>
                    </div>
                    <!--end::Customer change button-->
                    <!--begin::Notice-->
                    <div
                        class="notice d-flex bg-light-primary rounded border-primary border border-dashed rounded-3 p-6">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-stack flex-grow-1">
                            <!--begin::Content-->
                            <div class="fw-semibold">
                                <h4 class="text-gray-900 fw-bold">This is a very important privacy notice!</h4>
                                <div class="fs-6 text-gray-700">Writing headlines for blog posts is much science and
                                    probably cool audience.
                                    <a href="#" class="fw-bold">Learn more</a>.
                                </div>
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Notice-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Customer-->
            <!--begin::Pricing-->
            <div class="card card-flush pt-3 mb-5 mb-lg-10">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="fw-bold">Products</h2>
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-light-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_product">Add Product</button>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table wrapper-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 fw-semibold gy-4"
                            id="kt_subscription_products_table">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-300px">Product</th>
                                    <th class="min-w-100px">Qty</th>
                                    <th class="min-w-150px">Total</th>
                                    <th class="min-w-70px text-end">Remove</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">

                                <tr>
                                    <td>Beginner Plan</td>
                                    <td>1</td>
                                    <td>149.99 / Month</td>
                                    <td class="text-end">
                                        <!--begin::Delete-->
                                        <a href="#"
                                            class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px me-3"
                                            data-bs-toggle="tooltip" title="Delete" data-kt-action="product_remove">
                                            <i class="ki-duotone ki-trash fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </a>
                                        <!--end::Delete-->
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Table wrapper-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Pricing-->
            <!--begin::Payment method-->
            <div class="card card-flush pt-3 mb-5 mb-lg-10" data-kt-subscriptions-form="pricing">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2 class="fw-bold">Payment Method</h2>
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <a href="#" class="btn btn-light-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_new_card">New Method</a>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Options-->
                    <div id="kt_create_new_payment_method">
                        <!--begin::Option-->
                        <div class="py-1">
                            <!--begin::Header-->
                            <div class="py-3 d-flex flex-stack flex-wrap">
                                <!--begin::Toggle-->
                                <div class="d-flex align-items-center collapsible toggle" data-bs-toggle="collapse"
                                    data-bs-target="#kt_create_new_payment_method_1">
                                    <!--begin::Arrow-->
                                    <div class="btn btn-sm btn-icon btn-active-color-primary ms-n3 me-2">
                                        <i class="ki-duotone ki-minus-square toggle-on text-primary fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <i class="ki-duotone ki-plus-square toggle-off fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </div>
                                    <!--end::Arrow-->
                                    <!--begin::Logo-->
                                    <img src="assets/media/svg/card-logos/mastercard.svg" class="w-40px me-3" alt="" />
                                    <!--end::Logo-->
                                    <!--begin::Summary-->
                                    <div class="me-3">
                                        <div class="d-flex align-items-center fw-bold">Mastercard
                                            <div class="badge badge-light-primary ms-5">Primary</div>
                                        </div>
                                        <div class="text-muted">Expires Dec 2024</div>
                                    </div>
                                    <!--end::Summary-->
                                </div>
                                <!--end::Toggle-->
                                <!--begin::Input-->
                                <div class="d-flex my-3 ms-9">
                                    <!--begin::Radio-->
                                    <label class="form-check form-check-custom form-check-solid me-5">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            checked="checked" />
                                    </label>
                                    <!--end::Radio-->
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div id="kt_create_new_payment_method_1" class="collapse show fs-6 ps-10">
                                <!--begin::Details-->
                                <div class="d-flex flex-wrap py-5">
                                    <!--begin::Col-->
                                    <div class="flex-equal me-5">
                                        <table class="table table-flush fw-semibold gy-1">
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Name</td>
                                                <td class="text-gray-800">Emma Smith</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Number</td>
                                                <td class="text-gray-800">**** 5515</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Expires</td>
                                                <td class="text-gray-800">12/2024</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Type</td>
                                                <td class="text-gray-800">Mastercard credit card</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Issuer</td>
                                                <td class="text-gray-800">VICBANK</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">ID</td>
                                                <td class="text-gray-800">id_4325df90sdf8</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="flex-equal">
                                        <table class="table table-flush fw-semibold gy-1">
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Billing address</td>
                                                <td class="text-gray-800">AU</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Phone</td>
                                                <td class="text-gray-800">No phone provided</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Email</td>
                                                <td class="text-gray-800">
                                                    <a href="#"
                                                        class="text-gray-900 text-hover-primary">smith@kpmg.com</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Origin</td>
                                                <td class="text-gray-800">Australia
                                                    <div class="symbol symbol-20px symbol-circle ms-2">
                                                        <img src="assets/media/flags/australia.svg" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">CVC check</td>
                                                <td class="text-gray-800">Passed
                                                    <i class="ki-duotone ki-check-circle fs-2 text-success">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Details-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Option-->
                        <div class="separator separator-dashed"></div>
                        <!--begin::Option-->
                        <div class="py-1">
                            <!--begin::Header-->
                            <div class="py-3 d-flex flex-stack flex-wrap">
                                <!--begin::Toggle-->
                                <div class="d-flex align-items-center collapsible toggle collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#kt_create_new_payment_method_2">
                                    <!--begin::Arrow-->
                                    <div class="btn btn-sm btn-icon btn-active-color-primary ms-n3 me-2">
                                        <i class="ki-duotone ki-minus-square toggle-on text-primary fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <i class="ki-duotone ki-plus-square toggle-off fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </div>
                                    <!--end::Arrow-->
                                    <!--begin::Logo-->
                                    <img src="assets/media/svg/card-logos/visa.svg" class="w-40px me-3" alt="" />
                                    <!--end::Logo-->
                                    <!--begin::Summary-->
                                    <div class="me-3">
                                        <div class="d-flex align-items-center fw-bold">Visa</div>
                                        <div class="text-muted">Expires Feb 2022</div>
                                    </div>
                                    <!--end::Summary-->
                                </div>
                                <!--end::Toggle-->
                                <!--begin::Input-->
                                <div class="d-flex my-3 ms-9">
                                    <!--begin::Radio-->
                                    <label class="form-check form-check-custom form-check-solid me-5">
                                        <input class="form-check-input" type="radio" name="payment_method" />
                                    </label>
                                    <!--end::Radio-->
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div id="kt_create_new_payment_method_2" class="collapse fs-6 ps-10">
                                <!--begin::Details-->
                                <div class="d-flex flex-wrap py-5">
                                    <!--begin::Col-->
                                    <div class="flex-equal me-5">
                                        <table class="table table-flush fw-semibold gy-1">
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Name</td>
                                                <td class="text-gray-800">Melody Macy</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Number</td>
                                                <td class="text-gray-800">**** 6319</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Expires</td>
                                                <td class="text-gray-800">02/2022</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Type</td>
                                                <td class="text-gray-800">Visa credit card</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Issuer</td>
                                                <td class="text-gray-800">ENBANK</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">ID</td>
                                                <td class="text-gray-800">id_w2r84jdy723</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="flex-equal">
                                        <table class="table table-flush fw-semibold gy-1">
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Billing address</td>
                                                <td class="text-gray-800">UK</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Phone</td>
                                                <td class="text-gray-800">No phone provided</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Email</td>
                                                <td class="text-gray-800">
                                                    <a href="#"
                                                        class="text-gray-900 text-hover-primary">melody@altbox.com</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Origin</td>
                                                <td class="text-gray-800">United Kingdom
                                                    <div class="symbol symbol-20px symbol-circle ms-2">
                                                        <img src="assets/media/flags/united-kingdom.svg" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">CVC check</td>
                                                <td class="text-gray-800">Passed
                                                    <i class="ki-duotone ki-check fs-2 text-success"></i>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Details-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Option-->
                        <div class="separator separator-dashed"></div>
                        <!--begin::Option-->
                        <div class="py-1">
                            <!--begin::Header-->
                            <div class="py-3 d-flex flex-stack flex-wrap">
                                <!--begin::Toggle-->
                                <div class="d-flex align-items-center collapsible toggle collapsed"
                                    data-bs-toggle="collapse" data-bs-target="#kt_create_new_payment_method_3">
                                    <!--begin::Arrow-->
                                    <div class="btn btn-sm btn-icon btn-active-color-primary ms-n3 me-2">
                                        <i class="ki-duotone ki-minus-square toggle-on text-primary fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <i class="ki-duotone ki-plus-square toggle-off fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                    </div>
                                    <!--end::Arrow-->
                                    <!--begin::Logo-->
                                    <img src="assets/media/svg/card-logos/american-express.svg" class="w-40px me-3"
                                        alt="" />
                                    <!--end::Logo-->
                                    <!--begin::Summary-->
                                    <div class="me-3">
                                        <div class="d-flex align-items-center fw-bold">American Express
                                            <div class="badge badge-light-danger ms-5">Expired</div>
                                        </div>
                                        <div class="text-muted">Expires Aug 2021</div>
                                    </div>
                                    <!--end::Summary-->
                                </div>
                                <!--end::Toggle-->
                                <!--begin::Input-->
                                <div class="d-flex my-3 ms-9">
                                    <!--begin::Radio-->
                                    <label class="form-check form-check-custom form-check-solid me-5">
                                        <input class="form-check-input" type="radio" name="payment_method" />
                                    </label>
                                    <!--end::Radio-->
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div id="kt_create_new_payment_method_3" class="collapse fs-6 ps-10">
                                <!--begin::Details-->
                                <div class="d-flex flex-wrap py-5">
                                    <!--begin::Col-->
                                    <div class="flex-equal me-5">
                                        <table class="table table-flush fw-semibold gy-1">
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Name</td>
                                                <td class="text-gray-800">Max Smith</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Number</td>
                                                <td class="text-gray-800">**** 3584</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Expires</td>
                                                <td class="text-gray-800">08/2021</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Type</td>
                                                <td class="text-gray-800">American express credit card</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Issuer</td>
                                                <td class="text-gray-800">USABANK</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">ID</td>
                                                <td class="text-gray-800">id_89457jcje63</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="flex-equal">
                                        <table class="table table-flush fw-semibold gy-1">
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Billing address</td>
                                                <td class="text-gray-800">US</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Phone</td>
                                                <td class="text-gray-800">No phone provided</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Email</td>
                                                <td class="text-gray-800">
                                                    <a href="#" class="text-gray-900 text-hover-primary">max@kt.com</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">Origin</td>
                                                <td class="text-gray-800">United States of America
                                                    <div class="symbol symbol-20px symbol-circle ms-2">
                                                        <img src="assets/media/flags/united-states.svg" />
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted min-w-125px w-125px">CVC check</td>
                                                <td class="text-gray-800">Failed
                                                    <i class="ki-duotone ki-cross fs-2 text-danger">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Details-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Option-->
                    </div>
                    <!--end::Options-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Payment method-->

        </form>
        <!--end::Form-->
    </div>
    <!--end::Content-->
    <!--begin::Sidebar-->
    <div class="flex-column flex-lg-row-auto w-100 w-lg-250px w-xl-300px mb-10 order-1 order-lg-2">
        <!--begin::Card-->
        <div class="card card-flush pt-3 mb-0" data-kt-sticky="true" data-kt-sticky-name="subscription-summary"
            data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '250px', xl: '300px'}"
            data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="false"
            data-kt-sticky-zindex="95">
            <!--begin::Card header-->
            <div class="card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    <h2>Summary</h2>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0 fs-6">
                <!--begin::Section-->
                <div class="mb-7">
                    <!--begin::Title-->
                    <h5 class="mb-3">Customer details</h5>
                    <!--end::Title-->
                    <!--begin::Details-->
                    <div class="d-flex align-items-center mb-1">
                        <!--begin::Name-->
                        <a href="apps/customers/view.html" class="fw-bold text-gray-800 text-hover-primary me-2">Sean
                            Bean</a>
                        <!--end::Name-->
                        <!--begin::Status-->
                        <span class="badge badge-light-success">Active</span>
                        <!--end::Status-->
                    </div>
                    <!--end::Details-->
                    <!--begin::Email-->
                    <a href="#" class="fw-semibold text-gray-600 text-hover-primary">sean@dellito.com</a>
                    <!--end::Email-->
                </div>
                <!--end::Section-->
                <!--begin::Seperator-->
                <div class="separator separator-dashed mb-7"></div>
                <!--end::Seperator-->
                <!--begin::Section-->
                <div class="mb-7">
                    <!--begin::Title-->
                    <h5 class="mb-3">Product details</h5>
                    <!--end::Title-->
                    <!--begin::Details-->
                    <div class="mb-0">
                        <!--begin::Plan-->
                        <span class="badge badge-light-info me-2">Basic Bundle</span>
                        <!--end::Plan-->
                        <!--begin::Price-->
                        <span class="fw-semibold text-gray-600">$149.99 / Year</span>
                        <!--end::Price-->
                    </div>
                    <!--end::Details-->
                </div>
                <!--end::Section-->
                <!--begin::Seperator-->
                <div class="separator separator-dashed mb-7"></div>
                <!--end::Seperator-->
                <!--begin::Section-->
                <div class="mb-10">
                    <!--begin::Title-->
                    <h5 class="mb-3">Payment Details</h5>
                    <!--end::Title-->
                    <!--begin::Details-->
                    <div class="mb-0">
                        <!--begin::Card info-->
                        <div class="fw-semibold text-gray-600 d-flex align-items-center">Mastercard
                            <img src="assets/media/svg/card-logos/mastercard.svg" class="w-35px ms-2" alt="" />
                        </div>
                        <!--end::Card info-->
                        <!--begin::Card expiry-->
                        <div class="fw-semibold text-gray-600">Expires Dec 2024</div>
                        <!--end::Card expiry-->
                    </div>
                    <!--end::Details-->
                </div>
                <!--end::Section-->
                <!--begin::Actions-->
                <div class="mb-0">
                    <button type="submit" class="btn btn-primary" id="kt_subscriptions_create_button">
                        <!--begin::Indicator label-->
                        <span class="indicator-label">Create Subscription</span>
                        <!--end::Indicator label-->
                        <!--begin::Indicator progress-->
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        <!--end::Indicator progress-->
                    </button>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Sidebar-->
</div>

@endsection