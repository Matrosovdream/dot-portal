@if($errors->any())

    <div id="validation_errors"
        class="alert alert-danger border border-danger bg-light-danger d-flex flex-column p-5 mb-10 rounded"
        style="display: inline-block; min-width: 300px; max-width: 90%;">

        <h5 class="text-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill fs-2 me-2"></i>
            Please correct the following errors:
        </h5>

        <ul class="mb-0 ps-4" id="error_list">

            @foreach($errors->all() as $error)
                <li class="mb-2">
                    {{ $error }}
                </li>
            @endforeach

        </ul>
    </div>

@endif

