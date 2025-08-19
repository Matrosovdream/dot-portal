@foreach($sidebarMenu as $item)

    <div data-kt-menu-trigger="click" class="menu-item menu-accordion @if($item['active']) show @endif">
        <!--begin:Menu link-->
        @php
            $hasChilds = !empty($item['childs']);
            $isActive = $item['active'] ?? false;
            $hasUrl = !empty($item['url']);
        @endphp

        @if( $hasUrl )
            <a href="{{ $item['url'] }}" class="menu-link {{ $isActive ? 'active' : '' }}">
        @else
            <span class="menu-link">
        @endif

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

        @if( isset($item['alert']) )
            <x-alert-small />
        @endif  

        @if($hasChilds)
            <span class="menu-arrow"></span>
        @endif

        @if($hasUrl)
            </a>
        @else
            </span>
        @endif
        <!--end:Menu link-->

        <!--begin:Menu sub-->
        @if($hasChilds)
            <div class="menu-sub menu-sub-accordion">
                @foreach($item['childs'] as $child)
                    <div class="menu-item">
                        <a class="menu-link {{ $child['active'] ? 'active' : '' }}" href="{{ $child['url'] ?? '#' }}">
                            <span class="menu-bullet">
                                <span class="bullet bullet-dot"></span>
                            </span>
                            <span class="menu-title">{{ $child['title'] ?? '' }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
        <!--end:Menu sub-->
    </div>

@endforeach
