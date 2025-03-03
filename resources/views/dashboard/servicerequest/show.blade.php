@extends('dashboard.layouts.app')

@section('content')

    <div class="card">
        <div class="card-body p-lg-17">

            <div class="position-relative mb-17">

                <div class="overlay overlay-show">

                    <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-250px"
                        style="background-image:url('assets/media/stock/1600x800/img-1.jpg')">
                    </div>

                    <div class="overlay-layer rounded bg-black" style="opacity: 0.4"></div>
                </div>

                <div class="position-absolute text-white mb-8 ms-10 bottom-0">
                    <h3 class="text-white fs-2qx fw-bold mb-3 m">
                        {{ $service['name'] }}
                    </h3>
                    <div class="fs-5 fw-semibold">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut
                    </div>
                </div>

            </div>

            <div class="d-flex flex-column flex-lg-row mb-17">

                <div class="flex-lg-row-fluid me-0 me-lg-20">

                    <form 
                        action="{{ route('dashboard.servicegroups.update', $group['id']) }}"
                        class="form mb-15 fv-plugins-bootstrap5 fv-plugins-framework" 
                        method="POST"
                        >
                        @csrf

                        @include('dashboard.includes.errors.default')

                        <div class="row mb-5">

                            <div class="col-md-6 fv-row fv-plugins-icon-container">

                                <label class="required fs-5 fw-semibold mb-2">First Name</label>
                                <input type="text" class="form-control form-control-solid" placeholder="" name="first_name">

                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>

                            <div class="col-md-6 fv-row fv-plugins-icon-container">

                                <label class="required fs-5 fw-semibold mb-2">Last Name</label>
                                <input type="text" class="form-control form-control-solid" placeholder="" name="last_name">

                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>

                        </div>

                        <div class="separator mb-8"></div>

                        <button type="submit" class="btn btn-primary" id="kt_careers_submit_button">

                            <span class="indicator-label">Apply Now</span>

                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>

                        </button>

                    </form>

                </div>

                <div class="flex-lg-row-auto w-100 w-lg-275px w-xxl-350px">
                    <div class="card bg-light">

                        <div class="card-body">

                            <div class="mb-7">

                                <h2 class="fs-1 text-gray-800 w-bolder mb-6">
                                    {{ $service['name'] }} details
                                </h2>

                                <p class="fw-semibold fs-6 text-gray-600">
                                    {{ $service['description'] }}
                                </p>

                            </div>


                            <div class="mb-8">

                                <h4 class="text-gray-700 w-bolder mb-0">Our Achievements</h4>
                                <div class="my-2">

                                    <div class="d-flex align-items-center mb-3">
                                        <span class="bullet me-3"></span>
                                        <div class="text-gray-600 fw-semibold fs-6">Experience with JavaScript</div>
                                    </div>

                                </div>

                            </div>

                            <a href="pages/blog/post.html" class="link-primary fs-6 fw-semibold">Explore More</a>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection