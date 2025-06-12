<div id="kt_header_search" class="header-search d-flex align-items-stretch"
     data-kt-search-keypress="true"
     data-kt-search-min-length="2"
     data-kt-search-enter="enter"
     data-kt-search-layout="menu"
     data-kt-menu-trigger="auto"
     data-kt-menu-overflow="false"
     data-kt-menu-permanent="true"
     data-kt-menu-placement="bottom-end">
    <!--begin::Search toggle-->
    <div class="d-flex align-items-center" data-kt-search-element="toggle" id="kt_header_search_toggle">
        <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px">
            <i class="ki-duotone ki-magnifier fs-2">
                <span class="path1"></span><span class="path2"></span>
            </i>
        </div>
    </div>
    <!--end::Search toggle-->

    <!--begin::Menu-->
    <div data-kt-search-element="content"
         class="menu menu-sub menu-sub-dropdown p-7"
         style="width: 50vw; max-width: 100%;">
        <!--begin::Wrapper-->
        <div data-kt-search-element="wrapper">
            <!--begin::Form-->
            <form data-kt-search-element="form" class="w-100 position-relative mb-3" autocomplete="off">
                <i class="ki-duotone ki-magnifier fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-0">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                <input type="text" class="search-input form-control form-control-flush ps-10"
                       name="search" placeholder="Search..." data-kt-search-element="input" />
                <span class="search-spinner position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-1"
                      data-kt-search-element="spinner">
                    <span class="spinner-border h-15px w-15px align-middle text-gray-500"></span>
                </span>
                <span class="search-reset btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 d-none"
                      data-kt-search-element="clear">
                    <i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </span>
            </form>
            <!--end::Form-->

            <!--begin::Separator-->
            <div class="separator border-gray-200 mb-6"></div>
            <!--end::Separator-->

            <!--begin::Tabs-->
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6" id="searchTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="tab-documents" data-bs-toggle="tab" href="#search-tab-documents" role="tab">Documents</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="tab-drivers" data-bs-toggle="tab" href="#search-tab-drivers" role="tab">Drivers</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="tab-vehicles" data-bs-toggle="tab" href="#search-tab-vehicles" role="tab">Vehicles</a>
                </li>
            </ul>
            <!--end::Tabs-->

            <!--begin::Tab content-->
            <div class="tab-content" id="searchTabsContent" data-kt-search-element="results">
                <div class="tab-pane fade show active" id="search-tab-documents" role="tabpanel">
                    <div class="scroll-y pe-3" style="max-height: 300px;">
                        <!-- Document Item -->
                        <div class="mb-6">
                            <a href="/documents/1" class="fs-5 fw-bold text-hover-primary d-block mb-1">Employee Onboarding Guide</a>
                            <div class="text-muted fs-7 mb-2">A detailed document outlining the onboarding process for new employees in the organization.</div>
                            <div class="d-flex flex-wrap text-muted fs-8 gap-3">
                                <span><i class="bi bi-person"></i> HR Department</span>
                                <span><i class="bi bi-calendar"></i> Jun 5, 2025</span>
                                <span><i class="bi bi-file-earmark-text"></i> PDF</span>
                            </div>
                        </div>
                
                        <!-- Document Item -->
                        <div class="mb-6">
                            <a href="/documents/2" class="fs-5 fw-bold text-hover-primary d-block mb-1">Quarterly Marketing Report</a>
                            <div class="text-muted fs-7 mb-2">Performance insights and analytics for Q2 2025 with visual charts and department goals.</div>
                            <div class="d-flex flex-wrap text-muted fs-8 gap-3">
                                <span><i class="bi bi-person"></i> Marketing Team</span>
                                <span><i class="bi bi-calendar"></i> Jun 1, 2025</span>
                                <span><i class="bi bi-file-earmark-bar-graph"></i> Excel</span>
                            </div>
                        </div>
                
                        <!-- Document Item -->
                        <div class="mb-6">
                            <a href="/documents/3" class="fs-5 fw-bold text-hover-primary d-block mb-1">IT Security Policy</a>
                            <div class="text-muted fs-7 mb-2">An updated policy document outlining acceptable use and security protocols for all IT assets.</div>
                            <div class="d-flex flex-wrap text-muted fs-8 gap-3">
                                <span><i class="bi bi-person"></i> IT Dept</span>
                                <span><i class="bi bi-calendar"></i> May 28, 2025</span>
                                <span><i class="bi bi-shield-lock"></i> Word Doc</span>
                            </div>
                        </div>
                    </div>
                
                    <!-- Show All Button -->
                    <div class="text-center mt-4">
                        <a href="/documents" class="btn btn-sm btn-light-primary fw-semibold">
                            Show All Documents
                        </a>
                    </div>
                </div>
                
                <div class="tab-pane fade text-center" id="search-tab-drivers" role="tabpanel">
                    <p class="text-muted">No results</p>
                </div>
                <div class="tab-pane fade text-center  mt-4" id="search-tab-vehicles" role="tabpanel">
                    <p class="text-muted">No results</p>
                </div>
            </div>
            <!--end::Tab content-->
        </div>
        <!--end::Wrapper-->

    </div>
    <!--end::Menu-->
</div>
