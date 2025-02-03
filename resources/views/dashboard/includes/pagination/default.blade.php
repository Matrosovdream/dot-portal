@if ($paginator->hasPages())

    @php
        //dd($paginator->toArray());
    @endphp

<!--
            <div id=""
                class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                <div><select name="kt_ecommerce_products_table_length" aria-controls="kt_ecommerce_products_table"
                        class="form-select form-select-solid form-select-sm" id="dt-length-0">
                        <option value="10">10</option>
                    </select><label for="dt-length-0"></label></div>
            </div>
            <div id=""
                class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                <div class="dt-paging paging_simple_numbers">
                    <nav>
                        <ul class="pagination">
                            <li class="dt-paging-button page-item disabled"><button class="page-link previous"
                                    role="link" type="button" aria-controls="kt_ecommerce_products_table"
                                    aria-disabled="true" aria-label="Previous" data-dt-idx="previous" tabindex="-1"><i
                                        class="previous"></i></button></li>
                            <li class="dt-paging-button page-item active"><button class="page-link" role="link"
                                    type="button" aria-controls="kt_ecommerce_products_table" aria-current="page"
                                    data-dt-idx="0">1</button></li>
                            <li class="dt-paging-button page-item"><button class="page-link" role="link" type="button"
                                    aria-controls="kt_ecommerce_products_table" data-dt-idx="1">2</button></li>
                            <li class="dt-paging-button page-item"><button class="page-link" role="link" type="button"
                                    aria-controls="kt_ecommerce_products_table" data-dt-idx="2">3</button></li>
                            <li class="dt-paging-button page-item"><button class="page-link" role="link" type="button"
                                    aria-controls="kt_ecommerce_products_table" data-dt-idx="3">4</button></li>
                            <li class="dt-paging-button page-item"><button class="page-link" role="link" type="button"
                                    aria-controls="kt_ecommerce_products_table" data-dt-idx="4">5</button></li>
                            <li class="dt-paging-button page-item"><button class="page-link next" role="link"
                                    type="button" aria-controls="kt_ecommerce_products_table" aria-label="Next"
                                    data-dt-idx="next"><i class="next"></i></button></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
-->

    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
