@extends('dashboard.layouts.app')

@section('toolbar-buttons')
@endsection

@section('content')

    <div class="card card-flush">

        <form action="{{ route('dashboard.documents.index') }}" method="GET">

            <div class="card-header align-items-center py-5 gap-2 gap-md-5">

                <div class="card-title">

                </div>

                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">

                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" name="q" value="{{ request()->q ?? '' }}"
                            data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-12"
                            placeholder="Search Documents">
                    </div>

                    <button type="submit" class="btn btn-primary">Filter</button>

                </div>
            </div>

        </form>

        <div class="card-body pt-0">
            <div class="table-responsive">

                @if(count($documents['items']) == 0)
                    <div class="text-center mt-10">
                        <h4>No documents found</h4>
                    </div>
                @else

                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th>Name</th>
                                <th class="text-center"></th>
                                <th>Tags</th>
                                <th class="text-center">Extension</th>
                                <th>Size</th>
                                <th class="text-center">Added</th>
                                <th class="text-center">Download</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">

                            @foreach($documents['items'] as $document)

                                <tr>
                                    <td>
                                        <a href="{{ $document['downloadUrl'] }}">
                                            {{ $document['filename'] }} (download)
                                        </a>
                                    </td>
                                    <td>
                                        @if( $document['extension'] != 'pdf' )
                                            <a href="#" class="btn btn-primary btn-sm flex-shrink-0 me-3"
                                                data-bs-toggle="modal" data-bs-target="#kt_modal_filepreview_{{ $document['id'] }}">
                                                Preview
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $document['tagGrouped'] }}
                                    </td>
                                    <td class="text-center">
                                        {{ $document['extension'] }}
                                    </td>
                                    <td>
                                        {{ $document['sizeFormatted'] }}
                                    </td>
                                    <td class="text-center pe-0">
                                        {{ dateFormat( $document['Model']->created_at ) }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ $document['downloadUrl'] }}" class="fs-6">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>

                                @include('dashboard.modals.layout.file-preview', [
                                    'file_id' => $document['id']
                                ])

                            @endforeach

                        </tbody>
                    </table>

                @endif

            </div>


            <div id="" class="row">
                {{ $documents['Model']->appends(request()->query())->links('dashboard.includes.pagination.default') }}
            </div>

        </div>

    </div>

@endsection