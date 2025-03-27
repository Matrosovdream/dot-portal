@extends('dashboard.layouts.app')

@section('toolbar-buttons')
@endsection

@section('content')

<div class="card card-flush">

    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" data-kt-ecommerce-product-filter="search"
                    class="form-control form-control-solid w-250px ps-12" placeholder="Search Documents">
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        <div class="table-responsive">

            @if( count($documents['items']) == 0 )
                <div class="text-center">
                    <h4>No documents found</h4>
                </div>
            @else

                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-200px">Name</th>
                            <th class="min-w-200px">Tags</th>
                            <th class="min-w-200px">Extension</th>
                            <th class="min-w-200px">Size</th>
                            <th class="min-w-200px text-center">Added</th>
                            <th class="min-w-200px">Download</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">

                        @foreach($documents['items'] as $document)

                            <tr>
                                <td>
                                    {{ $document['name'] }}
                                </td>
                                <td>
                                    {{ implode( ', ', $document['tags'] ) }}
                                </td>
                                <td>
                                    {{ $document['extension'] }}
                                </td>
                                <td>
                                    {{ $document['size'] }}
                                </td>
                                <td class="text-center pe-0">
                                    {{ $document['Model']->created_at->format('d/m/Y') }}
                                </td>
                                <td>
                                    <a 
                                        href="{{ route('dashboard.documents.download', $document['Model']->id) }}" 
                                        class="btn btn-sm btn-primary">
                                        Download
                                    </a>
                                </td>
                                
                            </tr>

                        @endforeach

                    </tbody>
                </table>

            @endif

        </div>


        <div id="" class="row">
            {{ $documents['Model']->links('dashboard.includes.pagination.default') }}
        </div>

    </div>

</div>

@endsection