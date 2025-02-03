@extends('web.layouts.app')

@section('content')

<div class="container my-4">

    @include('web.account.orders.partials.backlink', ['url' => route('web.account.order', $order->id)])

    <h2 class="mb-25">
        {{ $order->getProduct()->name }} - {{ __('Declarations') }}
    </h2>

    <div class="row">
        <div class="row">
            <div class="col-md-3">
                @include('web.account.orders.partials.sidebar')
            </div>

            <div class="col-md-9">
                <!-- General Information Form -->
                <div class="card p-4">

                    <div class="application-section-head mb-25">
                        <h3 class="card-title">
                            {{ __('Declarations') }}
                        </h3>
                        <p class="card-text text-warning"></p>
                    </div>

                    <form method="POST"
                        action="{{ route('web.account.order.applicant.fields.update', ['order_id' => $order->id, 'applicant_id' => $applicant->id]) }}"
                        class="xb-item--form contact-from w-75 apply-form">
                        @csrf

                        @include(
                            'web.partials.fields-loop',
                            [
                                'values' => $fieldValues,
                                'fields' => $formFields,
                                'entity' => 'traveller'
                            ]
                        )

                        @php /*
                        @include('web.account.orders.partials.applicant-fields')
                        */ @endphp

                        <button type="submit" class="btn btn-primary" id="next-1">
                            {{ __('Save') }}
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>


    <script>

        $(document).ready(function () {

            $('#field-is_previous_country_deport-0').on('change.select2', function () {

                var blocks = [
                    'previous_country_deport_country',
                    'previous_country_deport_date',
                    'previous_country_deport_details'
                ];

                if ($(this).val() == 'yes') {
                    blocks.forEach(function (block) {
                        $('.field-block-' + block).show();
                        $('.field-block-' + block + ' select').select2();
                    });
                } else {
                    blocks.forEach(function (block) {
                        $('.field-block-' + block).hide();
                    });
                }

            });

            // Trigger the change event on page load
            $('#field-is_previous_country_deport-0').trigger('change.select2');

        });



    </script>

    @php 

        //dd($fields);
    @endphp

    @if($fields['is_previous_country_deport']['value'] == 'yes')
        <style>
            .field-block-previous_country_deport_country,
            .field-block-previous_country_deport_date,
            .field-block-previous_country_deport_details {
                display: block;
            }
        </style>
    @else
        <style>
            .field-block-previous_country_deport_country,
            .field-block-previous_country_deport_date,
            .field-block-previous_country_deport_details {
                display: none;
            }
        </style>
    @endif


    <style>
        .card-body {
            background-color: rgb(248 249 249);
        }

        .card-status {
            background-color: #d1ecf1;
            color: #0c5460;
            border-radius: 10px;
            padding: 4px 8px 2px 8px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .card-progress {
            background-color: #e9ecef;
            border-radius: 50%;
            padding: 5px;
            font-size: 0.8rem;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-country img {
            width: 20px;
            margin-right: 5px;
        }

        .btn-arrow {
            background: linear-gradient(90deg, #00d7b0, #00e65b);
            color: white;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            border: none;
        }

        .card-text {
            font-size: 15px;
        }
    </style>

    @endsection