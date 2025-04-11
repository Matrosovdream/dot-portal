<div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">

    <i class="ki-duotone ki-information fs-2tx text-warning me-4">
        <span class="path1"></span>
        <span class="path2"></span>
        <span class="path3"></span>
    </i>

    <div class="d-flex flex-stack flex-grow-1">

        <div class="fw-semibold">
            <h4 class="text-gray-900 fw-bold">{{ $title }}</h4>
            <div class="fs-6 text-gray-700">
                {{ $text }}

                @if( $link )
                    <a class="fw-bold" href="{{ $link }}">
                        {{ $link_url }}
                    </a>
                @endif
            </div>
        </div>

    </div>

</div>