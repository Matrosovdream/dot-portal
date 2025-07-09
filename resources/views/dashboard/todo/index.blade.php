@extends('dashboard.layouts.app')

<!-- Content -->
@section('content')

    @include('dashboard.todo.partials.navbar')

    <div class="card card-flush">

        <div class="card-body pt-0">
            <div class="table-responsive">

                @if(count($tasks['items']) == 0)
                    <div class="text-center mt-10">
                        <h4>No tasks found</h4>
                    </div>
                @else

                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">

                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="">Title</th>
                                <th>Category</th>
                                <th>Tab</th>
                                <th class=" text-center">Added</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">

                            @foreach($tasks['items'] as $task)

                                <tr>
                                    <td>
                                        {{ $task['title'] ?? '' }}
                                    </td>
                                    <td>
                                        @if(isset($task['category']))
                                            {{ ucfirst( $task['category'] ) }}

                                        @else
                                            <span class="text-muted">No category</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($task['subcategory']))
                                            {{ ucfirst( $task['subcategory'] ) }}
                                        @else
                                            <span class="text-muted">No tab</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ dateFormat( $task['Model']->created_at ) }}
                                    </td>
                                    <td>
                                        <a href="{{ $task['link'] }}" class="btn btn-sm btn-light-primary">
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
                {{ $tasks['Model']->appends(request()->query())->links('dashboard.includes.pagination.default') }}
            </div>

        </div>

    </div>

@endsection




