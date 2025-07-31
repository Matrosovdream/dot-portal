@extends('dashboard.layouts.app')

@section('toolbar-buttons')
@endsection

@section('content')

    <div class="card card-flush">

        <div class="card-body pt-0">
            <div class="table-responsive">

            @if(count($fees['items']) == 0)
                <div class="text-center mt-10">
                    <h4>No fees found</h4>
                </div>
            @else

                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="">Name</th>
                            <th class="">Price</th>
                            <th class="">Description</th>
                            <th class=""></th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">

                            @foreach($fees['items'] as $item)

                                <tr>
                                    <td>
                                        <a href="{{ route('dashboard.planfees.show', $item['id']) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">
                                            {{ $item['name'] }}
                                        </a>
                                    </td>
                                    <td class="">
                                        ${{ $item['price'] }}
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td class="text-end">
                                        <a 
                                            href="{{ route('dashboard.planfees.show', $item['id']) }}" 
                                            class="btn btn-sm btn-light-primary">
                                            <i class="fa fa-eye"></i> 
                                            View
                                        </a>
                                    </td>
                                </tr>

                            @endforeach

                    </tbody>
                </table>

            @endif

            </div>


            <div id="" class="row">
                @if( isset($fees['Model']) )
                    {{ $fees['Model']->appends(request()->query())->links('dashboard.includes.pagination.default') }}
                @endif
            </div>

        </div>

    </div>

@endsection