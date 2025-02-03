@extends('dashboard.layouts.app')

@section('content')

    <div class="card card-flush">

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="">ID</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Product</th>
                            <th class="text-center min-w-100px">Total</th>
                            <th class="text-center min-w-100px">Added</th>
                            <th class="text-center min-w-100px">Modified</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">

                        @foreach($orders as $order)

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('dashboard.my-orders.show', $order->id) }}"
                                            class="text-gray-800 text-hover-primary fs-5 fw-bold"
                                            data-kt-ecommerce-product-filter="product_name">
                                            {{ $order->id }}
                                        </a>
                                    </div>
                                </td>
                                <td class="text-center pe-0">
                                    @if ($order->status->slug == 'pending')
                                        <div class="badge badge-light-primary">{{ $order->status->name }}</div>
                                    @elseif ($order->status->slug == 'completed')
                                        <div class="badge badge-light-success">{{ $order->status->name }}</div>
                                    @elseif ($order->status->slug == 'cancelled')
                                        <div class="badge badge-light-danger">{{ $order->status->name }}</div>
                                    @else
                                        <div class="badge badge-light-primary">{{ $order->status->name }}</div>
                                    @endif
                                </td>
                                <td class="text-center pe-0">

                                    @foreach($order->cartProducts as $item)
                                        <a href="{{ route('dashboard.products.show', $item->product->id) }}"
                                            class="text-gray-800 text-hover-primary"
                                            data-kt-ecommerce-product-filter="product_name">
                                            {{ $item->product->name }}
                                            ( {{ $item->offer->name }} )
                                            <div class="extras">
                                                @foreach($item->product->extras as $extra)
                                                    <span class="">+ {{ $extra->name }}</span>
                                                @endforeach
                                            </div>
                                        </a>
                                    @endforeach

                                </td>
                                <td class="text-center pe-0">
                                    {{ $order->getTotal() }}$
                                </td>
                                <td class="text-center pe-0" data-order="Inactive">
                                    {{ $order->created_at->format('d/m/Y') }}
                                </td>
                                <td class="text-center pe-0" data-order="Inactive">
                                    {{ $order->updated_at->format('d/m/Y') }}
                                </td>
                            </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>

            <div id="" class="row">
                {{ $orders->links('dashboard.includes.pagination.default') }}
            </div>

        </div>

    </div>

@endsection

@section('toolbar-buttons')

@endsection

@section('footer-scripts')

@endsection