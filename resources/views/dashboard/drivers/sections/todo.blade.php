<div class="tab-pane" id="kt_ecommerce_customer_fields" role="tabpanel">
    <div class="card card-flush py-4">
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            data-bs-target="#kt_account_profile_details" aria-expanded="true"
            aria-controls="kt_account_profile_details">

            <div class="card-title m-0">
                <h3 class="fw-bold m-0">To Do list</h3>
            </div>

        </div>

        <div class="card-body pt-0">
            <div class="" data-kt-ecommerce-catalog-add-product="auto-options">

                <div id="kt_ecommerce_add_product_options">

                    @if(count($validation['errors']) > 0)

                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                                <thead>
                                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">Section</th>
                                        <th class="min-w-100px">Field</th>
                                        <th class="min-w-100px">Comment</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">

                                    @foreach( $validation['errors'] as $sectionCode=>$section )

                                        @foreach( $section as $error )

                                            <tr>

                                                <td class="pe-0">
                                                   {{ $validation['tabs'][ $sectionCode ]['title'] }}
                                                </td>

                                                <td class="pe-0">
                                                    {{ $error['title'] ?? '' }}
                                                </td>

                                                <td class="pe-0">
                                                    {{ $validation['task'] ?? '' }}
                                                </td>

                                            </tr>

                                        @endforeach

                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    @else

                        <div class="text-center mt-10">
                            <h4>No To Do tasks</h4>
                        </div>

                    @endif

                </div>

            </div>
        </div>

    </div>
</div>