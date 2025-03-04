@if(count($fields) > 0)

    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
            <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-100px">Title</th>
                    <th class="min-w-100px">Required</th>
                    <th class="min-w-100px">Default value</th>
                    <th class="min-w-100px">Placeholder</th>
                    <th class="min-w-100px text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">

                @foreach($fields as $field)

                    <tr>

                        <td class="pe-0">
                            {{ $field['field']['title'] }}
                        </td>

                        <td class="pe-0">
                            @if($field['required'])
                                <span class="badge badge-light-success">Yes</span>
                            @else
                                <span class="badge badge-light-danger">No</span>
                            @endif
                        </td>

                        <td class="pe-0">
                            {{ $field['default_value'] }}
                        </td>

                        <td class="pe-0">
                            {{ $field['placeholder'] }}
                        </td>

                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <i class="ki-duotone ki-down fs-5 ms-1"></i></a>

                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                data-kt-menu="true">

                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_product_form_field_{{ $field['id'] }}">
                                        Edit
                                    </a>

                                </div>

                                <div class="menu-item px-3">

                                    <form
                                        action="{{ route('dashboard.services.fields.destroy', ['service' => $service['id'], 'field_id' => $field['id']]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="menu-link px-3">
                                            Delete
                                        </button>

                                    </form>

                                </div>
                            </div>
                        </td>

                    </tr>

                @endforeach


            </tbody>
        </table>
    </div>

@endif
