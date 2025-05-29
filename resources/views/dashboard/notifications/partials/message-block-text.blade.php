<div class="timeline-item">

    <div class="timeline-line"></div>
    <div class="timeline-icon">
        <i class="ki-duotone ki-message-text-2 fs-2 text-gray-500">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
        </i>
    </div>

    <div class="timeline-content mb-10 mt-n1">
        <div class="pe-3 mb-5">

            <div class="fs-5 fw-semibold mb-2">
                {{ $notification['title'] }}
            </div>

            <div class="d-flex align-items-center mt-1 fs-6">
                <div class="text-muted me-2 fs-7">Added at {{ dateTimeFormat( $notification['Model']->created_at ) }}</div>
            </div>
        </div>

        <div class="overflow-auto pb-5">
            <div
                class="d-flex align-items-center border border-dashed border-gray-300 rounded min-w-750px px-7 py-3 mb-5">

                <div class="min-w-600px pe-2">
                    <p>
                        <p>
                            {!! $notification['message'] !!}
                        </p>
                    </p>
                </div>

                <div class="min-w-175px pe-2">
                    <span class="badge badge-light text-muted">News</span>
                </div>

                <a href="apps/projects/project.html" class="btn btn-sm btn-light btn-active-light-primary">
                    View
                </a>

            </div>
        </div>

    </div>
</div>