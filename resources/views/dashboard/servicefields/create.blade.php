@extends('dashboard.layouts.app')

@section('content')

    <form class="form" method="POST" action="{{ route('dashboard.servicefields.store') }}">
        @csrf

        <div class="d-flex flex-column flex-xl-row">
            <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">
                <div class="card mb-5 mb-xl-8">

                    <div class="card-body pt-5">

                        <div class="d-flex flex-stack fs-4 py-3">
                            <div class="fw-bold">{{ $title }}</div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="flex-lg-row-fluid ms-lg-15">

                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach

                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">

                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                            href="#kt_ecommerce_customer_general">General</a>
                    </li>

                </ul>

                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade active show" id="kt_ecommerce_customer_general" role="tabpanel">
                        <div class="card pt-4 mb-6 mb-xl-9">

                            <div class="card-body pt-0 pb-5">

                                <input type="hidden" name="action" value="save_general" />
                                <input type="hidden" name="entity" value="service" />

                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold mb-2 required">Title</label>
                                    <input type="text" name="title" value="{{ old('title') }}"
                                        class="form-control form-control-solid" placeholder="" />
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold mb-2 required">Slug</label>
                                    <input type="text" name="slug" value="{{ old('slug') }}"
                                        class="form-control form-control-solid" placeholder="" />
                                </div>

                                <div class="fv-row mb-7">

                                    <label class="fs-6 fw-semibold mb-2">
                                        <span class="required">Field type</span>
                                    </label>

                                    <select name="type" class="form-select form-select-solid" data-control="select2"
                                        data-hide-search="true">
                                        <option value="">Select type</option>
                                        @foreach($references['fieldTypes'] as $code => $type)
                                            <option value="{{ $code }}" {{ old('type') == $code ? 'selected' : '' }}>
                                                {{ $type['title'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>

                                @php /*
                                <div class="fv-row mb-7">

                                    <label class="fs-6 fw-semibold mb-2">
                                        <span class="required">Reference</span>
                                    </label>

                                    <select name="reference_code" class="form-select form-select-solid"
                                        data-control="select2" data-hide-search="true">
                                        <option value="">Select reference</option>
                                        @foreach($references as $code => $ref)
                                            <option value="{{ $code }}" {{ old('reference') == $code ? 'selected' : '' }}>
                                                {{ $ref['title'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                */ @endphp

                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold mb-2 required">Placeholder</label>
                                    <input type="text" name="placeholder" value="{{ old('placeholder') }}"
                                        class="form-control form-control-solid" placeholder="" />
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold mb-2 required">Tooltip</label>
                                    <input type="text" name="tooltip" value="{{ old('tooltip') }}"
                                        class="form-control form-control-solid" placeholder="" />
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold mb-2 required">Description</label>
                                    <input type="text" name="description" value="{{ old('description') }}"
                                        class="form-control form-control-solid" placeholder="" />
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-semibold mb-2 required">Icon</label>
                                    <input type="text" name="icon" value="{{ old('icon') }}"
                                        class="form-control form-control-solid" placeholder="" />
                                </div>

                                <div class="d-flex justify-content-end">

                                    <button type="submit" id="kt_ecommerce_customer_profile_submit"
                                        class="btn btn-light-primary">
                                        <span class="indicator-label">Save</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>

                                </div>


                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </form>

@endsection