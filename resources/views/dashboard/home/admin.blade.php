@extends('dashboard.layouts.app')

@section('toolbar-buttons')



@endsection

@section('content')



<!--begin::Row-->
<div class="row gx-5 gx-xl-10 mb-xl-10">
    <!--begin::Col-->
    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-10">
        <!--begin::Card widget 4-->
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Currency-->
                        <span class="fs-4 fw-semibold text-gray-500 me-1 align-self-start">$</span>
                        <!--end::Currency-->
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">69,700</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <span class="badge badge-light-success fs-base">
                            <i class="ki-duotone ki-arrow-up fs-5 text-success ms-n1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>2.2%</span>
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Expected Earnings</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body pt-2 pb-4 d-flex align-items-center">
                <!--begin::Chart-->
                <div class="d-flex flex-center me-5 pt-2">
                    <div id="kt_card_widget_4_chart" style="min-width: 70px; min-height: 70px" data-kt-size="70"
                        data-kt-line="11"></div>
                </div>
                <!--end::Chart-->
                <!--begin::Labels-->
                <div class="d-flex flex-column content-justify-center w-100">
                    <!--begin::Label-->
                    <div class="d-flex fs-6 fw-semibold align-items-center">
                        <!--begin::Bullet-->
                        <div class="bullet w-8px h-6px rounded-2 bg-danger me-3"></div>
                        <!--end::Bullet-->
                        <!--begin::Label-->
                        <div class="text-gray-500 flex-grow-1 me-4">Shoes</div>
                        <!--end::Label-->
                        <!--begin::Stats-->
                        <div class="fw-bolder text-gray-700 text-xxl-end">$7,660</div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Label-->
                    <!--begin::Label-->
                    <div class="d-flex fs-6 fw-semibold align-items-center my-3">
                        <!--begin::Bullet-->
                        <div class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
                        <!--end::Bullet-->
                        <!--begin::Label-->
                        <div class="text-gray-500 flex-grow-1 me-4">Gaming</div>
                        <!--end::Label-->
                        <!--begin::Stats-->
                        <div class="fw-bolder text-gray-700 text-xxl-end">$2,820</div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Label-->
                    <!--begin::Label-->
                    <div class="d-flex fs-6 fw-semibold align-items-center">
                        <!--begin::Bullet-->
                        <div class="bullet w-8px h-6px rounded-2 me-3" style="background-color: #E4E6EF"></div>
                        <!--end::Bullet-->
                        <!--begin::Label-->
                        <div class="text-gray-500 flex-grow-1 me-4">Others</div>
                        <!--end::Label-->
                        <!--begin::Stats-->
                        <div class="fw-bolder text-gray-700 text-xxl-end">$45,257</div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Label-->
                </div>
                <!--end::Labels-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 4-->
        <!--begin::Card widget 5-->
        <div class="card card-flush h-md-50 mb-xl-10">
            <!--begin::Header-->
            <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                    <!--begin::Info-->
                    <div class="d-flex align-items-center">
                        <!--begin::Amount-->
                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">1,836</span>
                        <!--end::Amount-->
                        <!--begin::Badge-->
                        <span class="badge badge-light-danger fs-base">
                            <i class="ki-duotone ki-arrow-down fs-5 text-danger ms-n1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>2.2%</span>
                        <!--end::Badge-->
                    </div>
                    <!--end::Info-->
                    <!--begin::Subtitle-->
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Orders This Month</span>
                    <!--end::Subtitle-->
                </div>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Card body-->
            <div class="card-body d-flex align-items-end pt-0">
                <!--begin::Progress-->
                <div class="d-flex align-items-center flex-column mt-3 w-100">
                    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                        <span class="fw-bolder fs-6 text-gray-900">1,048 to Goal</span>
                        <span class="fw-bold fs-6 text-gray-500">62%</span>
                    </div>
                    <div class="h-8px mx-3 w-100 bg-light-success rounded">
                        <div class="bg-success rounded h-8px" role="progressbar" style="width: 62%;" aria-valuenow="50"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <!--end::Progress-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card widget 5-->
    </div>

    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-10">
        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">
                    <div class="d-flex align-items-center">

                        <span class="fs-4 fw-semibold text-gray-500 me-1 align-self-start">$</span>
                        <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">2,420</span>

                        <span class="badge badge-light-success fs-base">
                            <i class="ki-duotone ki-arrow-up fs-5 text-success ms-n1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>2.6%
                        </span>

                    </div>

                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Average Daily Sales</span>

                </div>
            </div>

            <div class="card-body d-flex align-items-end px-0 pb-0">
                <div id="kt_card_widget_6_chart" class="w-100" style="height: 80px"></div>
            </div>

        </div>

        <div class="card card-flush h-md-50 mb-xl-10">
            <div class="card-header pt-5">
                <div class="card-title d-flex flex-column">

                    <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">6.3k</span>
                    <span class="text-gray-500 pt-1 fw-semibold fs-6">New Customers This Month</span>

                </div>
            </div>

            <div class="card-body d-flex flex-column justify-content-end pe-0">
                <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Some text here</span>
            </div>

        </div>
        
    </div>

    <div class="col-lg-12 col-xl-12 col-xxl-6 mb-5 mb-xl-0">
        <div class="card card-flush overflow-hidden h-md-100">
            <div class="card-header py-5">

                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-900">Sales This Months</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Users from all channels</span>
                </h3>

                <div class="card-toolbar">

                    <button class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
                        <i class="ki-duotone ki-dots-square fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </button>

                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                        data-kt-menu="true">

                        <div class="menu-item px-3">
                            <div class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">Quick Actions</div>
                        </div>

                        <div class="separator mb-3 opacity-75"></div>

                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3">New Ticket</a>
                        </div>

                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3">New Customer</a>
                        </div>

                        <div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">

                            <a href="#" class="menu-link px-3">
                                <span class="menu-title">New Group</span>
                                <span class="menu-arrow"></span>
                            </a>

                            <div class="menu-sub menu-sub-dropdown w-175px py-4">

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">Admin Group</a>
                                </div>

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">Staff Group</a>
                                </div>

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">Member Group</a>
                                </div>

                            </div>

                        </div>

                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3">New Contact</a>
                        </div>

                        <div class="separator mt-3 opacity-75"></div>

                        <div class="menu-item px-3">
                            <div class="menu-content px-3 py-3">
                                <a class="btn btn-primary btn-sm px-4" href="#">Generate Reports</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                <div class="px-9 mb-5">
                    <div class="d-flex mb-2">
                        <span class="fs-4 fw-semibold text-gray-500 me-1">$</span>
                        <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">14,094</span>
                    </div>
                    <span class="fs-6 fw-semibold text-gray-500">Another $48,346 to Goal</span>
                </div>

                <div id="kt_charts_widget_3" class="min-h-auto ps-4 pe-6" style="height: 300px"></div>
            </div>



        </div>
    </div>
</div>




@endsection