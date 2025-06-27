@extends('dashboard.layouts.app')


@section('content')

    <div class="card card-flush">

        @include('dashboard.saferweb.includes.filter')

        <div class="card-body pt-0">
            <div class="table-responsive">

                @if(count($items['items']) == 0)

                    <div class="text-center mt-10">
                        <h4>No crashes found</h4>
                    </div>

                @else

                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="">Report Number</th>
                                <th class="">Unit Vin</th>
                                <th class="">Dot Number</th>
                                <th class="">Report state</th>
                                <th class="">Date</th>
                                <th class=""></th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">

                            @foreach($items['items'] as $item)

                                <tr>

                                    <td>
                                        {{ $item['report_number'] }}
                                    </td>

                                    <td>
                                        {{ $item['unit_vin'] }}
                                    </td>

                                    <td>
                                        {{ $item['dot_number'] }}
                                    </td>

                                    <td>
                                        {{ $item['report_state'] }}
                                    </td>

                                    <td>
                                        {{ dateFormat($item['report_date']) }}
                                    </td>

                                    <td>
                                        <a href="{{ route('dashboard.saferweb.crashes.show', $item['id']) }}" class="btn btn-sm btn-light-primary">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>
                    </table>

                @endif

            </div>

            <div id="" class="row">
                {{ $items['Model']->appends(request()->query())->links('dashboard.includes.pagination.default') }}
            </div>

        </div>

    </div>

@endsection