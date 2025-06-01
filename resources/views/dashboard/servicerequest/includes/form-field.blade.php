@if($field['field']['type'] == 'text' || $field['field']['type'] == 'email')

    <div class="col-md-6 fv-row fv-plugins-icon-container mb-5">
        <label for="field-{{ $field['field']['slug'] }}" class="form-label fs-5 fw-semibold mb-2">
            {{ $field['field']['title'] }}
        </label>
        <input type="text" class="form-control form-control-soli" id="field-{{ $field['field']['slug'] }}"
            name="fields[{{ $field['field']['id'] }}]" value="{{ $field['value'] ?? '' }}">
    </div>

@endif

@if($field['field']['type'] == 'textarea')

    <div class="col-md-6 fv-row fv-plugins-icon-container mb-5">
        <label for="field-{{ $field['field']['slug'] }}" class="form-label required fs-5 fw-semibold mb-2">
            {{ $field['field']['title'] }}
        </label>
        <textarea class="form-control form-control-soli" id="field-{{ $field['field']['slug'] }}"
            name="fields[{{ $field['field']['id'] }}]">{{ $field['value'] ?? '' }}</textarea>
    </div>

@endif

@if($field['field']['type'] == 'date')

    <div class="col-md-6 fv-row fv-plugins-icon-container mb-5">
        <label for="field-{{ $field['field']['slug'] }}" class="form-label required fs-5 fw-semibold mb-2">
            {{ $field['field']['title'] }}
        </label>
        <input type="text" class="form-control form-control-soli datepicker" id="field-{{ $field['field']['slug'] }}"
            name="fields[{{ $field['field']['id'] }}]" value="{{ $field['value'] ?? '' }}">
    </div>

@endif

@if($field['field']['type'] == 'select')

    <div class="col-md-6 fv-row fv-plugins-icon-container">
        <label for="field-{{ $field['field']['slug'] }}" class="required fs-5 fw-semibold mb-2">
            {{ $field['field']['title'] }} {{ $field['required'] ? '*' : '' }}
        </label>

        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
            data-placeholder="{{ $field['field']['title'] }}" data-kt-ecommerce-order-filter=""
            id="field-{{ $field['field']['slug'] }}" name="fields[{{ $field['field']['slug'] }}]">
            <option selected disabled></option>
            @foreach($field['options'] as $option)
                <option value="{{ $option['value'] }}" @if($option['value'] == $field['value']) selected @endif>
                    {{ $option['title'] }}
                </option>
            @endforeach
        </select>
    </div>

@endif

@if($field['field']['type'] == 'file')

    <div class="mb-10 fv-row fv-plugins-icon-container">
        <label for="field-{{ $field['field']['slug'] }}" class="form-label fs-5 fw-semibold mb-2">
            {{ $field['field']['title'] }} {{ $field['required'] ? '*' : '' }}
        </label>
        @if(isset($field['value']))
            <a href="{{ Storage::url($field['value']->path) }}" target="_blank">
                {{ __('Download') }}
            </a>
        @endif  

        <div class="w-50">
            <x-file-uploader 
                inputName="fields[{{ $field['field']['id'] }}]"
                :value="(null)"
                :accept="'image/*,application/pdf'"
                :multiple="false"
                :required="false"
                :label="'Upload file'"
                :note="'Upload 1 image or PDF'"
                :description="''"
            />
        </div>

    </div>

    @php /*
    <div class="mb-10 fv-row fv-plugins-icon-container">
        <label for="field-{{ $field['field']['slug'] }}" class="form-label  w-100">
            {{ $field['field']['title'] }} {{ $field['required'] ? '*' : '' }}
        </label>
        @if(isset($field['value']))
            <a href="{{ Storage::url($field['value']->path) }}" target="_blank">
                {{ __('Download') }}
            </a>
        @endif  
        <input type="file" class="form-control form-control-soli" id="field-{{ $field['field']['slug'] }}"
            name="fields[{{ $field['field']['slug'] }}]">
    </div>
    */ @endphp

@endif

@if($field['field']['type'] == 'radio')

    <div class="mb-10 fv-row fv-plugins-icon-container">
        <label for="field-{{ $field['field']['slug'] }}" class="form-label">
            {{ $field['field']['title'] }} {{ $field['required'] ? '*' : '' }}
        </label>
        @foreach($field['options'] as $option)
            <div class="form-check">
                <label class="form-check" for="field-{{ $field['field']['slug'] }}-{{ $option['value'] }}">
                    <input class="form-check" type="radio" value="{{ $option['value'] }}"
                        id="field-{{ $field['field']['slug'] }}-{{ $option['value'] }}"
                        name="fields[{{ $field['field']['slug'] }}]" @if($option['value'] == $field['value']) checked @endif>
                    {{ $option['title'] }}
                </label>
            </div>
        @endforeach
    </div>

@endif

@if($field['field']['type'] == 'checkbox')

<div class="mb-10 fv-row fv-plugins-icon-container">
    <label for="field-{{ $field['field']['slug'] }}" class="form-label">
        {{ $field['field']['title'] }} {{ $field['required'] ? '*' : '' }}
    </label>
    <div class="form-check">
        <input class="form-check w-75" type="checkbox" id="field-{{ $field['field']['slug'] }}" @if($field['value'] == 1) checked @endif name="fields[{{ $field['field']['slug'] }}]">
                </div>
            </div>

        @endif

@section('footer-scripts')

    <script type="text/javascript">
        jQuery(document).ready(function () {

            // Date filter
            jQuery('.datepicker').flatpickr({
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
            });
        });
    </script>

@endsection