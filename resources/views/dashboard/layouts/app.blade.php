<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="light">

    <head>
        
        @php /*
        @include('web.includes.metas')
        */ @endphp

        <title>{{ $title }}</title>

        @stack('styles_top')
        @stack('scripts_top')

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" rel="stylesheet"/>

        <script>
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
        </script>

        @php /*
        @vite(
            [
                // CSS
                'resources/assets/admin/css/style.bundle.css', 
                'resources/assets/admin/css/style.bundle.css', 
                'resources/assets/admin/plugins/custom/fullcalendar/fullcalendar.bundle.css',
                'resources/assets/admin/plugins/custom/datatables/datatables.bundle.css',
                'resources/assets/admin/plugins/global/plugins.bundle.css',
                'resources/assets/admin/css/style.bundle.css',
                'resources/assets/admin/css/extra.css',
                // JS
                'resources/js/app.js'
        ])
        */ @endphp

        <!-- Scripts -->
        @foreach([
            '/assets/admin/plugins/custom/fullcalendar/fullcalendar.bundle.css',
            '/assets/admin/plugins/custom/datatables/datatables.bundle.css',
            '/assets/admin/plugins/global/plugins.bundle.css',
            '/assets/admin/css/style.bundle.css',
            '/assets/admin/css/extra.css',
        ] as $style)
            <link href="{{ asset($style) }}" rel="stylesheet"/>
        @endforeach

        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    </head>

    <body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">


        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

                <!-- Header -->
                @include('dashboard.includes.header')
                <!-- End of Header -->

                <!--begin::Wrapper-->
                <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

                    <!--begin::Sidebar-->
                    @include('dashboard.includes.sidebar')
                    <!--end::Sidebar-->

                    <!--begin::Main-->
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">

                        <!--begin::Content wrapper-->
                        <div class="d-flex flex-column flex-column-fluid">

                            <!--begin::Toolbar-->
							<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                                @include('dashboard.includes.toolbar')
                            </div>
                            <!--end::Toolbar-->

                            <!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-fluid">
                                    @yield('content')
                                </div>
                                <!--end::Content container-->
                            </div>
                            
                        </div>

                        <!--begin::Footer-->
						<div id="kt_app_footer" class="app-footer">
                            @include('dashboard.includes.footer')
                        </div>
                        <!--end::Footer-->

                    </div>
                    <!--end:::Main-->

                </div>
                <!--end::Wrapper-->
            
            </div>
        </div>

        <!--begin::Drawers-->
        <?php /*
        @include('dashboard.includes.drawers')
        */ ?>
        <!--end::Drawers-->

        <!-- Modals -->
        @include('dashboard.includes.modals')
        <!-- End of Modals -->

        <!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<!--end::Scrolltop-->

        @stack('styles_bottom')
        @stack('scripts_bottom')

        <!--begin::Javascript-->
        <script>var hostUrl = "{{ asset('/assets/admin/') }}";</script>
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="{{ asset('/assets/admin/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('/assets/admin/js/scripts.bundle.js') }}"></script>
        <!--end::Global Javascript Bundle-->
        <!--begin::Vendors Javascript(used for this page only)-->
        <script src="{{ asset('/assets/admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <!--end::Vendors Javascript-->
        <!--begin::Custom Javascript(used for this page only)-->
        <script src="{{ asset('/assets/admin/js/custom/apps/ecommerce/sales/listing.js') }}"></script>
        <script src="{{ asset('/assets/admin/js/widgets.bundle.js') }}"></script>
        <script src="{{ asset('/assets/admin/js/custom/widgets.js') }}"></script>
        <script src="{{ asset('/assets/admin/js/custom/apps/chat/chat.js') }}"></script>
        <script src="{{ asset('/assets/admin/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
        <script src="{{ asset('/assets/admin/js/custom/utilities/modals/create-app.js') }}"></script>
        <script src="{{ asset('/assets/admin/js/custom/utilities/modals/users-search.js') }}"></script>
        <!--end::Custom Javascript-->
        <!--end::Javascript-->

        @yield('footer-scripts')

            <script type="text/javascript">
                jQuery(document).ready(function () {

                    // Date filter
                    jQuery('.datepicker').flatpickr({
                        altInput: true,
                        altFormat: "F j, Y",
                        dateFormat: "Y-m-d",
                    });
                });
            </script>


    </body>

</html>
