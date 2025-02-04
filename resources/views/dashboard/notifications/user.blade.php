@extends('dashboard.layouts.app')

@section('toolbar-buttons')



@endsection

@section('content')


<div class="card">
    <div class="card-header card-header-stretch">

        <div class="card-title d-flex align-items-center">
            <i class="ki-duotone ki-calendar-8 fs-1 text-primary me-3 lh-0">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
                <span class="path4"></span>
                <span class="path5"></span>
                <span class="path6"></span>
            </i>
            <h3 class="fw-bold m-0 text-gray-800">{{ \Carbon\Carbon::now()->format('M d, Y') }}</h3>
        </div>

        <div class="card-toolbar m-0">

            <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bold" role="tablist">
                <li class="nav-item" role="presentation">
                    <a id="kt_activity_today_tab" class="nav-link justify-content-center text-active-gray-800 active"
                        data-bs-toggle="tab" role="tab" href="#kt_activity_today">Today</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a id="kt_activity_week_tab" class="nav-link justify-content-center text-active-gray-800"
                        data-bs-toggle="tab" role="tab" href="#kt_activity_week">Week</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a id="kt_activity_month_tab" class="nav-link justify-content-center text-active-gray-800"
                        data-bs-toggle="tab" role="tab" href="#kt_activity_month">Month</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a id="kt_activity_year_tab"
                        class="nav-link justify-content-center text-active-gray-800 text-hover-gray-800"
                        data-bs-toggle="tab" role="tab" href="#kt_activity_year">2024</a>
                </li>
            </ul>

        </div>
    </div>

    <div class="card-body">

        <div class="tab-content">

            <div id="kt_activity_today" class="card-body p-0 tab-pane fade show active" role="tabpanel"
                aria-labelledby="kt_activity_today_tab">
                <div class="timeline timeline-border-dashed">

                    @foreach($notifications['items'] as $notification)

                        @include(
                            'dashboard.notifications.partials.message-block-text',
                            ['notification' => $notification]
                        )

                    @endforeach

                </div>
            </div>

            <div id="kt_activity_week" class="card-body p-0 tab-pane fade show" role="tabpanel"
                aria-labelledby="kt_activity_week_tab">
                2
            </div>

            <div id="kt_activity_month" class="card-body p-0 tab-pane fade show role=" tabpanel"
                aria-labelledby="kt_activity_today_tab">
                3
            </div>

            <div id="kt_activity_year" class="card-body p-0 tab-pane fade show" role="tabpanel"
                aria-labelledby="kt_activity_today_tab">
                4
            </div>


        </div>

    </div>
</div>



@endsection