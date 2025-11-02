@extends('dashboard.layouts.app')

@section('toolbar-buttons')

@endsection

@section('content')

<div class="card card-flush pb-0 bgi-position-y-center bgi-no-repeat mb-10" style="background-size: auto calc(100% + 10rem); background-position-x: 100%; background-image: url('assets/media/illustrations/sketchy-1/4.png')">
 
    <div class="card-body pt-10">
    
    <div class="row g-6 align-items-center">
        <div class="col-md-4">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-45px me-4">
                    <div class="symbol-label bg-light-primary">
                        <i class="ki-duotone ki-abstract-39 fs-2 text-primary">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div>
                    <div class="text-muted fs-7">Company</div>
                    <div class="fs-5 fw-bold">
                        {{ $company['name'] ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-45px me-4">
                    <div class="symbol-label bg-light-success">
                        <i class="ki-duotone ki-shield-tick fs-2 text-success">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <div class="text-muted fs-7">Clearinghouse Enrollment</div>
                                                        <div class="d-flex align-items-center gap-2">
                            <span class="badge badge-light">Not Registered</span>
                            <a href="http://127.0.0.1:8000/dashboard/clearing-house/register-company" class="btn btn-sm btn-primary">Register Company</a>
                        </div>
                                                
                    </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-45px me-4">
                    <div class="symbol-label bg-light-info">
                        <i class="ki-duotone ki-search-list fs-2 text-info">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <div class="text-muted fs-7">Query Balance</div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="fs-5 fw-bold">0</div>
                        <span class="text-muted">remaining</span>
                        <a href="http://127.0.0.1:8000/dashboard/clearing-house/buy-queries" class="btn btn-sm btn-light-info">Buy Queries</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</div>

@endsection
