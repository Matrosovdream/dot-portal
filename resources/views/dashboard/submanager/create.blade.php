@extends('dashboard.layouts.app')

@section('content')

    <form class="form" method="POST" action="{{ route('dashboard.usersubscriptions.store') }}" id="kt_ecommerce_customer_profile">
        @csrf

        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                <div class="card mb-5 mb-xl-8">

                    <div class="card-body pt-5">

                        <div class="d-flex flex-stack fs-4 py-3">
                            <div class="fw-bold">Create new subscription</div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="flex-lg-row-fluid ms-lg-15">

                <div class="tab-pane fade active show" id="kt_ecommerce_customer_general" role="tabpanel">

                    @php /*
                    @include('dashboard.includes.errors.default')
                    */ @endphp

                    @include('dashboard.submanager.sections.create-user-block')

                    @include('dashboard.submanager.sections.create-company-block')

                    @include('dashboard.submanager.sections.create-sub-block')

                    <!-- Submit button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                            <span class="indicator-label">Save</span>
                            <span class="indicator-progress">Please wait... 
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>

                </div>

            </div>
        </div>

    </form>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- JS Logic -->
    <script>
        document.getElementById('usdot').addEventListener('blur', function () {
            const usdot = this.value.trim();
        
            if (!usdot) return;
        
            // Show loader
            document.getElementById('usdot-loader').classList.remove('d-none');
    
            // Show loader
            const loader = document.getElementById('usdot-loader');
            loader.classList.remove('d-none');

            console.log('Fetching USDOT data for:', usdot);
        
            fetch('/retrieve-usdot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({ usdot })
            })
            .then(response => response.json())
            .then(data => {

                // Fill the fields
                document.getElementById('company_name').value = data.company_name || '';
                //document.getElementById('trucks_number').value = data.trucks_number || '';
                document.getElementById('drivers_number').value = data.drivers_number || '';
    
                if( data.trucks_number ) {
                    //setTruckNumber(data.trucks_number);
                }
                if( data.drivers_number ) {
                    //setDriverNumber(data.drivers_number);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Optional: show error to user
            })
            .finally(() => {
                setTimeout(() => {
                    loader.classList.add('d-none');
                }, 100);
            });
        });
    </script>

@endsection