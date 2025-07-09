<ul class="nav flex-wrap border-transparent fw-bold">


    @foreach($menu as $item)
        @php
            $isActive = request()->routeIs($item['route']);
        @endphp
        <li class="nav-item my-1">
            <a class="btn btn-color-gray-600 btn-active-secondary btn-active-color-primary fw-bolder fs-8 
                        fs-lg-base nav-link px-3 px-lg-8 mx-1 text-uppercase {{ $isActive ? 'active' : '' }}" 
                href="{{ $item['url'] }}">
                {{ $item['title'] }}
            </a>
        </li>
    @endforeach

</ul>