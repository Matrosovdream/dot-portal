<!--begin::Notifications-->
<div class="app-navbar-item ms-1 ms-md-4">

    <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" id="kt_menu_item_wow">
        <i class="ki-duotone ki-notification-status fs-2">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
            <span class="path4"></span>
        </i>
    </div>

    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">

        <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('{{ asset('assets/admin/media/misc/menu-header-bg.jpg') }}')">

            <h3 class="text-white fw-semibold px-9 mt-10 mb-6">
                Notifications
                <span class="fs-8 opacity-75 ps-3">
                {{ $notifications['Model']->count() }} reports
                </span>
            </h3>

            <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
                <li class="nav-item">
                    <a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab" href="#kt_topbar_notifications_1">
                        All
                    </a>
                </li>
                <!--
                <li class="nav-item">
                    <a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab" href="#kt_topbar_notifications_2">Updates</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#kt_topbar_notifications_3">Logs</a>
                </li>
                -->
            </ul>

        </div>

        <div class="tab-content">

            <div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">

                <div class="scroll-y mh-325px my-5 px-8">

                    @forelse ($notifications['items'] as $item)
                        <div class="d-flex flex-stack py-4">
                
                            <div class="d-flex align-items-center">
                
                                <div class="symbol symbol-35px me-4">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-abstract-28 fs-2 text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </span>
                                </div>
                
                                <div class="mb-0 me-2">
                                    <a href="{{ $item['link'] ?? '#' }}" class="fs-6 text-gray-800 text-hover-primary fw-bold">
                                        {{ $item['title'] ?? '' }}
                                    </a>
                                    <div class="text-gray-500 fs-7">
                                        {{ $item['message'] ?? '' }}
                                    </div>
                                </div>
                
                            </div>
                
                            <span class="badge badge-light fs-8">
                                {{ \Carbon\Carbon::parse($item['Model']->created_at)->diffForHumans() }}
                            </span>
                
                        </div>
                    @empty
                        <div class="text-center text-gray-500 fw-semibold py-2">
                            No notifications found.
                        </div>
                    @endforelse
                
                </div>
                
                @if( $notifications['Model']->count() > 0 )

                    <div class="py-3 text-center border-top">
                        <a href="{{ route('dashboard.notifications') }}" class="btn btn-color-gray-600 btn-active-color-primary">
                            View All 
                            <i class="ki-duotone ki-arrow-right fs-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </a>
                    </div>

                @endif

            </div>

        </div>

    </div>

</div>
<!--end::Notifications-->
