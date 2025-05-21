@foreach($sidebarMenu as $item)

    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if($item['active']) show @endif">
        <!--begin:Menu link-->
        <span class="menu-link">
            <span class="menu-icon">
                <i class="ki-duotone {{ $item['icon'] }} fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
            </span>
            <span class="menu-title">
                {{ $item['title'] }}
            </span>
            <span class="menu-arrow"></span>
        </span>
        <!--end:Menu link-->
        <!--begin:Menu sub-->
        <div class="menu-sub menu-sub-accordion">

            @foreach($item['childs'] as $child)

                <div class="menu-item ">
                    <!--begin:Menu link-->
                    <a class="menu-link @if($child['active']) active @endif" href="{{ $child['url'] ?? '#' }}">
                        <span class="menu-bullet">
                            <span class="bullet bullet-dot"></span>
                        </span>
                        <span class="menu-title">{{ $child['title'] ?? '' }}</span>
                    </a>
                    <!--end:Menu link-->
                </div>

            @endforeach

        </div>
        <!--end:Menu sub-->
    </div>

@endforeach